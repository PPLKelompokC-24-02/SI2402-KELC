<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Support\Navigation;
use PHPUnit\Framework\Attributes\DataProvider;

class HomeTest extends ApiTestCase
{
    public static function roles(): array
    {
        return [['mahasiswa'], ['admin'], ['assessor']];
    }

    #[DataProvider('roles')]
    public function test_every_role_logs_in_to_the_same_home_view(string $role): void
    {
        $this->postJson('/login', ['email' => $role.'@careercompass.test', 'password' => config('career_compass.demo_password')])
            ->assertOk()->assertJsonPath('redirect', url('/home'));
        $response = $this->get('/home');
        $response->assertOk()->assertViewIs('home')->assertSee('Selamat Datang')->assertSee('Career Compass')
            ->assertSee('Kenali potensimu, eksplorasi pilihan karier, dan rencanakan langkah pengembangan dirimu.')
            ->assertSee($this->user($role)->name)->assertSee($this->user($role)->email)
            ->assertSee('aria-current="page"', false)->assertSee('Segera Hadir')
            ->assertDontSee('<table', false)->assertDontSee('<canvas', false)->assertDontSee('name="nim"', false);

        $this->get('/')->assertRedirect(route('home'));
        $this->get('/login')->assertRedirect(route('home'));
        $this->get('/register')->assertRedirect(route('home'));
    }

    public function test_home_requires_an_authenticated_browser_session(): void
    {
        $this->get('/home')->assertRedirect(route('login'));
        $this->getJson('/home')->assertUnauthorized();
        $this->withToken($this->tokenFor())->get('/home')->assertRedirect(route('login'));
    }

    public function test_student_sees_only_student_menu(): void
    {
        $this->actingAs($this->user(), 'web')->get('/home')->assertOk()
            ->assertSee('Pengembangan Karier')->assertSee('Career Assessment &amp; Academic Analysis', false)
            ->assertSee('Career Exploration &amp; Career Path', false)->assertSee('Career Action Plan')
            ->assertSee('Career Event')->assertSee('CV Builder')->assertSee('Career Progress Tracking')
            ->assertDontSee('Manajemen Profesi')->assertDontSee('Manajemen Pengguna')->assertDontSee('Pengajuan Assessment');
    }

    public function test_admin_sees_only_admin_menu(): void
    {
        $this->actingAs($this->user('admin'), 'web')->get('/home')->assertOk()
            ->assertSee('Manajemen Data')->assertSee('Manajemen Profesi')->assertSee('Mata Kuliah')
            ->assertSee('Career Path')->assertSee('Bank Soal Assessment')->assertSee('Template Action Plan')
            ->assertSee('Administrasi')->assertSee('Manajemen Pengguna')->assertSee('Monitoring Assessment')
            ->assertDontSee('CV Builder')->assertDontSee('Career Progress Tracking')->assertDontSee('Pengajuan Assessment');
    }

    public function test_assessor_sees_only_assessor_menu(): void
    {
        $this->actingAs($this->user('assessor'), 'web')->get('/home')->assertOk()
            ->assertSee('Penilaian Karier')->assertSee('Pengajuan Assessment')
            ->assertSee('Review Assessment &amp; Data Akademik', false)->assertSee('Evaluasi &amp; Rekomendasi Karier', false)
            ->assertSee('Publikasi Hasil Analisis')->assertSee('Riwayat Peninjauan')
            ->assertDontSee('Manajemen Profesi')->assertDontSee('Manajemen Pengguna')->assertDontSee('CV Builder');
    }

    #[DataProvider('roles')]
    public function test_missing_frontends_are_disabled_without_api_or_broken_links(string $role): void
    {
        $this->actingAs($this->user($role), 'web');
        $sections = Navigation::forRole($role);
        foreach ($sections as $section) {
            foreach ($section['items'] as $item) {
                if (in_array($item['route'], ['home', 'logout', 'admin.professions.index'], true)) {
                    $this->assertNotNull($item['url']);
                } else {
                    $this->assertNull($item['url']);
                    $this->assertFalse($item['active']);
                }
            }
        }
        $this->get('/home')->assertDontSee('href="#"', false)->assertDontSee('href="http://localhost/api/', false);
    }

    public function test_frontend_route_can_be_connected_using_navigation_configuration(): void
    {
        $this->actingAs($this->user('admin'), 'web')->get('/home')
            ->assertSee('href="'.url('/admin/professions').'"', false);
        $this->get('/admin/professions')->assertOk()->assertSee('Tambah Profesi');
        $this->actingAs($this->user(), 'web')->get('/admin/professions')->assertForbidden();
    }

    public function test_navigation_does_not_enable_an_api_route_with_a_matching_name(): void
    {
        config(['navigation.menus.mahasiswa' => [[
            'title' => 'Pengembangan Karier',
            'items' => [['label' => 'Career Exploration', 'icon' => 'compass', 'route' => 'professions.index']],
        ]]]);
        $this->assertNull(Navigation::forRole('mahasiswa')[0]['items'][0]['url']);
    }

    #[DataProvider('roles')]
    public function test_logout_returns_every_role_to_landing_and_revokes_home_access(string $role): void
    {
        $this->actingAs($this->user($role), 'web')->post('/logout')->assertRedirect(route('landing'));
        $this->assertGuest('web');
        $this->get('/home')->assertRedirect(route('login'));
    }

    public function test_unrecognized_role_cannot_access_home(): void
    {
        $role = Role::firstOrCreate(['name' => 'unsupported']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'web')->get('/home')->assertForbidden();
    }
}
