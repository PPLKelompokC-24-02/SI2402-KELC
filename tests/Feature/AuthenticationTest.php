<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;

class AuthenticationTest extends ApiTestCase
{
    private function registration(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Mahasiswa Baru',
            'email' => 'baru@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'nim' => '1202230099',
        ], $overrides);
    }

    public function test_registration_creates_only_student_and_hashes_password(): void
    {
        $response = $this->postJson('/api/auth/register', $this->registration(['email' => ' BARU@EXAMPLE.COM ']));
        $response->assertCreated()->assertJsonPath('data.user.role', 'mahasiswa')
            ->assertJsonPath('data.user.email', 'baru@example.com')
            ->assertJsonPath('data.token_type', 'Bearer')->assertJsonMissingPath('data.user.password');

        $user = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('SecurePassword123!', $user->password));
        $this->assertNotSame('SecurePassword123!', $user->password);
        $this->withToken($response->json('data.token'))->getJson('/api/auth/me')->assertOk()->assertJsonPath('data.id', $user->id);
    }

    public function test_registration_rejects_role_injection(): void
    {
        $this->postJson('/api/auth/register', $this->registration(['role' => 'admin', 'role_id' => Role::where('name', 'admin')->value('id')]))
            ->assertUnprocessable()->assertJsonValidationErrors(['role', 'role_id']);
        $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
    }

    public function test_password_rejects_excessive_bytes_and_null_characters(): void
    {
        foreach ([str_repeat('A1', 37), str_repeat('é', 36).'A1', "Password123\0"] as $password) {
            $this->postJson('/api/auth/register', $this->registration(['password' => $password, 'password_confirmation' => $password]))
                ->assertUnprocessable()->assertJsonValidationErrors('password');
        }
        $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
    }

    public function test_registration_rejects_missing_fields_duplicate_email_and_weak_password(): void
    {
        $this->postJson('/api/auth/register')->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'password']);
        $this->postJson('/api/auth/register', $this->registration([
            'email' => 'MAHASISWA@CAREERCOMPASS.TEST',
            'password' => 'short', 'password_confirmation' => 'different',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }

    public static function roles(): array
    {
        return [['admin'], ['assessor'], ['mahasiswa']];
    }

    #[DataProvider('roles')]
    public function test_each_role_can_login_and_read_own_profile(string $role): void
    {
        $response = $this->postJson('/api/auth/login', ['email' => strtoupper($role.'@careercompass.test'), 'password' => config('career_compass.demo_password')]);
        $response->assertOk()->assertJsonPath('data.user.role', $role)->assertJsonMissingPath('data.user.password');
        $this->withToken($response->json('data.token'))->getJson('/api/auth/me')
            ->assertOk()->assertJsonPath('data.role', $role)->assertJsonMissingPath('data.password');
    }

    public function test_login_rejects_invalid_credentials_and_malformed_input(): void
    {
        $this->postJson('/api/auth/login', ['email' => 'admin@careercompass.test', 'password' => 'wrong'])->assertUnauthorized();
        $this->postJson('/api/auth/login', ['email' => 'unknown@example.com', 'password' => 'wrong'])->assertUnauthorized();
        $this->postJson('/api/auth/login', ['email' => ['invalid'], 'password' => 'wrong'])->assertUnprocessable();
        $this->postJson('/api/auth/login', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/api/auth/login', ['email' => 'admin@careercompass.test', 'password' => 'wrong'])->assertUnauthorized();
        }
        $this->postJson('/api/auth/login', ['email' => 'admin@careercompass.test', 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_private_routes_return_json_401_even_without_accept_header(): void
    {
        $this->get('/api/auth/me')->assertUnauthorized()->assertHeader('content-type', 'application/json');
        $this->postJson('/api/auth/logout')->assertUnauthorized();
        $this->putJson('/api/auth/profile', ['name' => 'No access'])->assertUnauthorized();
        $this->getJson('/api/professions')->assertUnauthorized();
        $this->getJson('/api/admin/professions')->assertUnauthorized();
        $this->withToken('invalid-token')->getJson('/api/auth/me')->assertUnauthorized();
    }

    public function test_logout_revokes_only_current_token(): void
    {
        $current = $this->tokenFor();
        $other = $this->tokenFor();
        $this->withToken($current)->postJson('/api/auth/logout')->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->app['auth']->forgetGuards();
        $this->withToken($current)->getJson('/api/auth/me')->assertUnauthorized();
        $this->app['auth']->forgetGuards();
        $this->withToken($other)->getJson('/api/auth/me')->assertOk();
    }

    public function test_profile_updates_only_current_user_and_rejects_role_changes(): void
    {
        $this->authenticate()->putJson('/api/auth/profile', ['name' => 'Nama Baru', 'bio' => 'Tertarik pada analisis data.', 'phone' => '08123456789', 'email' => 'BARU@EXAMPLE.COM'])
            ->assertOk()->assertJsonPath('data.name', 'Nama Baru')->assertJsonPath('data.email', 'baru@example.com');
        $this->assertDatabaseHas('users', ['email' => 'admin@careercompass.test', 'name' => 'Admin Career Compass']);
        $this->putJson('/api/auth/profile', ['role' => 'admin', 'role_id' => 1])->assertUnprocessable()->assertJsonValidationErrors(['role', 'role_id']);
        $this->assertTrue(User::where('email', 'baru@example.com')->firstOrFail()->hasRole('mahasiswa'));
    }

    public function test_profile_validates_unique_email_nim_and_field_lengths(): void
    {
        $this->authenticate()->putJson('/api/auth/profile', ['email' => 'admin@careercompass.test', 'bio' => str_repeat('a', 2001)])
            ->assertUnprocessable()->assertJsonValidationErrors(['email', 'bio']);
        $this->putJson('/api/auth/profile', ['email' => 'mahasiswa@careercompass.test', 'nim' => '1202230001'])->assertOk();
    }

    public function test_password_change_requires_current_password_and_revokes_other_tokens(): void
    {
        $other = $this->tokenFor();
        $this->authenticate()->putJson('/api/auth/profile', ['password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123'])
            ->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->putJson('/api/auth/profile', ['password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123', 'current_password' => 'wrong'])
            ->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->putJson('/api/auth/profile', ['password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123', 'current_password' => config('career_compass.demo_password')])
            ->assertOk()->assertJsonMissingPath('data.password');
        $this->assertTrue(Hash::check('NewPassword123', $this->user()->password));
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->app['auth']->forgetGuards();
        $this->withToken($other)->getJson('/api/auth/me')->assertUnauthorized();
    }
}
