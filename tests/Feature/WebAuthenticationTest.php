<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WebAuthenticationTest extends ApiTestCase
{
    public function test_landing_and_login_render_accessible_form_without_reset_password_link(): void
    {
        foreach (['/', '/login'] as $path) {
            $this->get($path)->assertOk()->assertSee('Langkah berikutnya,')->assertSee('Selamat datang')
                ->assertSee('name="_token"', false)->assertSee('autocomplete="current-password"', false)
                ->assertSee('Ingat saya')->assertSee('Daftar sekarang')->assertDontSee('Lupa kata sandi');
        }
        $this->get('/register')->assertOk()->assertSee('Nama lengkap')->assertSee('Konfirmasi kata sandi')
            ->assertSee('autocomplete="new-password"', false)->assertDontSee('name="role"', false);
    }

    public function test_browser_login_authenticates_with_session_and_remember_cookie(): void
    {
        $response = $this->postJson('/login', [
            'email' => 'MAHASISWA@CAREERCOMPASS.TEST',
            'password' => config('career_compass.demo_password'),
            'remember' => '1',
        ]);
        $response->assertOk()->assertJsonPath('redirect', route('home'))->assertJsonMissingPath('token');
        $this->assertAuthenticatedAs($this->user(), 'web');
        $this->assertNotEmpty($this->user()->fresh()->remember_token);
        $response->assertCookie(auth('web')->getRecallerName());
        $this->get('/')->assertRedirect(route('home'));
        $this->get('/home')->assertOk()->assertSee('Mahasiswa Demo')->assertSee('Selamat Datang')->assertSee('Logout')->assertDontSee('data-auth-form', false);
    }

    public function test_browser_login_rejects_invalid_credentials_and_invalid_input(): void
    {
        $this->postJson('/login', ['email' => 'mahasiswa@careercompass.test', 'password' => 'wrong'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->assertGuest('web');
        $this->postJson('/login', ['email' => 'invalid', 'remember' => 'maybe'])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password', 'remember']);
    }

    public function test_browser_registration_logs_in_only_student_and_issues_no_api_token(): void
    {
        $this->postJson('/register', [
            'name' => 'Mahasiswa Baru', 'email' => 'BARU@EXAMPLE.COM',
            'password' => 'SecurePassword123!', 'password_confirmation' => 'SecurePassword123!',
        ])->assertOk()->assertJsonPath('redirect', route('home'));
        $user = User::where('email', 'baru@example.com')->firstOrFail();
        $this->assertTrue($user->hasRole('mahasiswa'));
        $this->assertTrue(Hash::check('SecurePassword123!', $user->password));
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_browser_registration_returns_backend_errors_for_duplicate_email_password_and_role_injection(): void
    {
        $this->postJson('/register', [
            'name' => 'Mahasiswa', 'email' => 'mahasiswa@careercompass.test',
            'password' => 'weak', 'password_confirmation' => 'different', 'role' => 'admin', 'role_id' => 1,
        ])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password', 'role', 'role_id']);
        $this->assertDatabaseCount('users', 3);
        $this->assertGuest('web');
    }

    public function test_forms_work_without_javascript_and_show_validation_errors(): void
    {
        $this->from('/login')->post('/login', ['email' => 'mahasiswa@careercompass.test', 'password' => 'wrong'])
            ->assertRedirect('/login')->assertSessionHasErrors('email')->assertSessionHasInput('email', 'mahasiswa@careercompass.test');
        $this->withCookie(config('session.cookie'), session()->getId())->get('/login')->assertSee('Email atau kata sandi tidak sesuai.');
        $this->post('/login', ['email' => 'mahasiswa@careercompass.test', 'password' => config('career_compass.demo_password')])->assertRedirect(route('home'));
        $this->assertAuthenticated('web');
    }

    public function test_browser_logout_ends_session_and_preserves_separate_api_tokens(): void
    {
        $this->user()->createToken('other-device');
        $this->actingAs($this->user(), 'web')->post('/logout')->assertRedirect(route('landing'));
        $this->assertGuest('web');
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->get('/')->assertSee('Kamu sudah keluar dari akun.')->assertSee('Masuk ke akun');
    }

    public function test_browser_login_uses_existing_rate_limits(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/login', ['email' => 'mahasiswa@careercompass.test', 'password' => 'wrong'])->assertUnprocessable();
        }
        $this->postJson('/login', ['email' => 'mahasiswa@careercompass.test', 'password' => 'wrong'])->assertTooManyRequests();
    }
}
