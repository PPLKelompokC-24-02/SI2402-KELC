<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\Profession;
use App\Models\User;
use Database\Seeders\DemoUserSeeder;
use LogicException;

class RoleAuthorizationTest extends ApiTestCase
{
    public function test_admin_can_change_user_role_and_previous_tokens_are_revoked(): void
    {
        $student = $this->user();
        $token = $student->createToken('before-role-change')->plainTextToken;
        $this->authenticate('admin')->putJson('/api/admin/users/'.$student->id.'/role', ['role' => 'assessor'])
            ->assertOk()->assertJsonPath('data.role', 'assessor')->assertJsonMissingPath('data.password');
        $this->assertSame(0, $student->tokens()->count());
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson('/api/auth/me')->assertUnauthorized();
        $login = $this->postJson('/api/auth/login', ['email' => $student->email, 'password' => config('career_compass.demo_password')]);
        $login->assertOk()->assertJsonPath('data.user.role', 'assessor');
        $this->app['auth']->forgetGuards();
        $this->withToken($login->json('data.token'))->getJson('/api/assessor/me')->assertOk();
    }

    public function test_admin_cannot_change_own_role_or_assign_unknown_role(): void
    {
        $admin = $this->user('admin');
        $this->authenticate('admin')->putJson('/api/admin/users/'.$admin->id.'/role', ['role' => 'mahasiswa'])->assertForbidden();
        $this->putJson('/api/admin/users/'.$this->user()->id.'/role', ['role' => 'superadmin'])->assertUnprocessable()->assertJsonValidationErrors('role');
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_admin_can_promote_student_and_demote_another_admin(): void
    {
        $student = $this->user();
        $this->authenticate('admin')->putJson('/api/admin/users/'.$student->id.'/role', ['role' => 'admin'])->assertOk();
        $this->putJson('/api/admin/users/'.$student->id.'/role', ['role' => 'mahasiswa'])->assertOk();
        $this->assertSame(1, User::whereHas('role', fn ($query) => $query->where('name', 'admin'))->count());
    }

    public function test_non_admins_cannot_manage_user_roles(): void
    {
        foreach (['mahasiswa', 'assessor'] as $role) {
            $this->authenticate($role)->putJson('/api/admin/users/'.$this->user('admin')->id.'/role', ['role' => 'mahasiswa'])->assertForbidden();
        }
    }

    public function test_assessor_area_is_limited_to_assessor_role(): void
    {
        $this->authenticate('assessor')->getJson('/api/assessor/me')->assertOk()->assertJsonPath('data.role', 'assessor');
        $this->getJson('/api/professions')->assertForbidden();
        $this->authenticate('admin')->getJson('/api/assessor/me')->assertForbidden();
        $this->authenticate()->getJson('/api/assessor/me')->assertForbidden();
    }

    public function test_repeated_seed_preserves_existing_users_and_profession_edits(): void
    {
        $profession = Profession::firstOrFail();
        $profession->update(['is_active' => false, 'description' => 'Diubah oleh admin.']);
        $profession->competencies()->sync([Competency::firstOrFail()->id]);
        $this->seed();
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('roles', 3);
        $this->assertDatabaseCount('professions', 5);
        $this->assertFalse($profession->fresh()->is_active);
        $this->assertSame('Diubah oleh admin.', $profession->fresh()->description);
        $this->assertSame(1, $profession->competencies()->count());
    }

    public function test_demo_seeder_refuses_production_environment(): void
    {
        $this->app->instance('env', 'production');
        $this->expectException(LogicException::class);
        $this->seed(DemoUserSeeder::class);
    }
}
