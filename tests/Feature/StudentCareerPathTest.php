<?php

namespace Tests\Feature;

use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Profession;
use App\Models\Role;
use App\Models\User;

class StudentCareerPathTest extends ApiTestCase
{
    /**
     * AC-01: Mahasiswa dapat membuka Career Path dari halaman profesi yang dipilih.
     */
    public function test_student_can_open_career_path_from_selected_profession_page(): void
    {
        $profession = Profession::where('is_active', true)->where('publication_status', 'published')->firstOrFail();

        // Mahasiswa membuka halaman detail profesi (overview)
        $response = $this->actingAs($this->user(), 'web')->get(route('careers.show', $profession));
        $response->assertOk()
            ->assertViewIs('careers.show')
            ->assertSee('Tahapan Jalur Karier')
            ->assertSee('Lihat Jalur Karier')
            ->assertSee(route('careers.show', ['profession' => $profession, 'tab' => 'path']), false);

        // Mahasiswa mengeklik tab Jalur Karier
        $pathResponse = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));
        $pathResponse->assertOk()
            ->assertViewIs('careers.show')
            ->assertSee('Jalur Karier')
            ->assertSee('Kenali tahapan perkembangan dan jenjang karier yang tersedia untuk profesi '.$profession->name);
    }

    /**
     * AC-02: Sistem menampilkan gambaran profesi dan urutan tahapan perkembangan karier sesuai sort_order.
     */
    public function test_career_overview_and_ordered_career_path_stages_are_displayed(): void
    {
        $category = CareerCategory::firstOrFail();
        $profession = Profession::create([
            'career_category_id' => $category->id,
            'name' => 'Fullstack Developer Test',
            'summary' => 'Ringkasan profesi fullstack developer.',
            'description' => 'Deskripsi lengkap peran fullstack developer di industri perangkat lunak.',
            'responsibilities' => ['Mengembangkan frontend dan backend.'],
            'entry_level' => 'Sarjana Informatika',
            'is_active' => true,
            'publication_status' => 'published',
        ]);

        // Tambahkan tahapan dengan urutan acak untuk memverifikasi sorting sort_order
        $profession->careerPathStages()->create([
            'title' => 'Senior Developer',
            'description' => 'Memimpin perancangan sistem dan arsitektur aplikasi berskala besar.',
            'sort_order' => 3,
        ]);
        $profession->careerPathStages()->create([
            'title' => 'Junior Developer',
            'description' => 'Mengembangkan fitur dasar dan memperbaiki bug di bawah supervisi.',
            'sort_order' => 1,
        ]);
        $profession->careerPathStages()->create([
            'title' => 'Mid-Level Developer',
            'description' => 'Merancang dan mengimplementasikan modul mandiri serta REST API.',
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));

        $response->assertOk()
            // Gambaran profesi (Overview)
            ->assertSee('Fullstack Developer Test')
            ->assertSee($category->name)
            // Tahapan terurut berdasarkan sort_order
            ->assertSee('Tahap 1')
            ->assertSee('Junior Developer')
            ->assertSee('Mengembangkan fitur dasar dan memperbaiki bug di bawah supervisi.')
            ->assertSee('Tahap 2')
            ->assertSee('Mid-Level Developer')
            ->assertSee('Merancang dan mengimplementasikan modul mandiri serta REST API.')
            ->assertSee('Tahap 3')
            ->assertSee('Senior Developer')
            ->assertSee('Memimpin perancangan sistem dan arsitektur aplikasi berskala besar.');

        // Verifikasi urutan tampilan teks di dalam HTML
        $content = $response->getContent();
        $posStage1 = strpos($content, 'Junior Developer');
        $posStage2 = strpos($content, 'Mid-Level Developer');
        $posStage3 = strpos($content, 'Senior Developer');

        $this->assertNotFalse($posStage1);
        $this->assertNotFalse($posStage2);
        $this->assertNotFalse($posStage3);
        $this->assertTrue($posStage1 < $posStage2 && $posStage2 < $posStage3, 'Tahapan karier harus tampil sesuai urutan sort_order.');
    }

    /**
     * AC-03: Sistem menampilkan kompetensi yang diperlukan pada setiap tahapan apabila tersedia,
     * serta empty state yang informatif jika tahapan belum memiliki kompetensi.
     */
    public function test_competencies_mapped_to_each_stage_are_displayed(): void
    {
        $profession = Profession::where('is_active', true)->where('publication_status', 'published')->firstOrFail();

        $compSql = Competency::firstOrCreate(['name' => 'SQL Querying Test'], ['description' => 'Kemampuan kueri SQL relasional.']);
        $compGit = Competency::firstOrCreate(['name' => 'Git Versioning Test'], ['description' => 'Penguasaan git dan version control.']);

        // Tahap A memiliki kompetensi
        $stageWithComp = $profession->careerPathStages()->create([
            'title' => 'Tahapan dengan Kompetensi',
            'description' => 'Tahapan yang memiliki kompetensi terhubung.',
            'sort_order' => 1,
        ]);
        $stageWithComp->competencies()->sync([$compSql->id, $compGit->id]);

        // Tahap B tidak memiliki kompetensi
        $stageWithoutComp = $profession->careerPathStages()->create([
            'title' => 'Tahapan Tanpa Kompetensi',
            'description' => 'Tahapan tanpa pemetaan kompetensi.',
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));

        $response->assertOk()
            ->assertSee('Tahapan dengan Kompetensi')
            ->assertSee('SQL Querying Test')
            ->assertSee('Git Versioning Test')
            ->assertSee('Tahapan Tanpa Kompetensi')
            ->assertSee('Belum ada kompetensi yang dipetakan pada tahapan ini.');
    }

    /**
     * AC-04: Sistem menampilkan tab mata kuliah relevan dengan graceful empty state (menunggu US-02).
     */
    public function test_relevant_courses_tab_displays_graceful_empty_state_without_error(): void
    {
        $profession = Profession::where('is_active', true)->where('publication_status', 'published')->firstOrFail();

        $response = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'courses']));

        $response->assertOk()
            ->assertSee('Mata Kuliah yang Relevan')
            ->assertSee('Informasi mata kuliah yang mendukung pengembangan kompetensi profesi '.$profession->name)
            ->assertSee('Data belum tersedia')
            ->assertSee('Informasi mata kuliah yang relevan akan ditambahkan ketika data tersedia.');
    }

    /**
     * AC-05: Informasi Career Path dan mata kuliah sesuai dengan profesi yang sedang dibuka (tidak saling mencampur).
     */
    public function test_career_path_is_scoped_to_the_current_profession_without_data_mixing(): void
    {
        $category = CareerCategory::firstOrFail();

        $profA = Profession::create([
            'career_category_id' => $category->id,
            'name' => 'Profesi Alfa Khusus',
            'description' => 'Deskripsi profesi alfa.',
            'responsibilities' => ['Tanggung jawab alfa.'],
            'is_active' => true,
            'publication_status' => 'published',
        ]);
        $profB = Profession::create([
            'career_category_id' => $category->id,
            'name' => 'Profesi Beta Khusus',
            'description' => 'Deskripsi profesi beta.',
            'responsibilities' => ['Tanggung jawab beta.'],
            'is_active' => true,
            'publication_status' => 'published',
        ]);

        $profA->careerPathStages()->create([
            'title' => 'Tahap Spesifik Alfa 123',
            'description' => 'Deskripsi tahapan alfa unik.',
            'sort_order' => 1,
        ]);

        $profB->careerPathStages()->create([
            'title' => 'Tahap Spesifik Beta 456',
            'description' => 'Deskripsi tahapan beta unik.',
            'sort_order' => 1,
        ]);

        // Buka Career Path Profesi Alfa
        $responseA = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profA, 'tab' => 'path']));
        $responseA->assertOk()
            ->assertSee('Profesi Alfa Khusus')
            ->assertSee('Tahap Spesifik Alfa 123')
            ->assertDontSee('Tahap Spesifik Beta 456');

        // Buka Career Path Profesi Beta
        $responseB = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profB, 'tab' => 'path']));
        $responseB->assertOk()
            ->assertSee('Profesi Beta Khusus')
            ->assertSee('Tahap Spesifik Beta 456')
            ->assertDontSee('Tahap Spesifik Alfa 123');
    }

    /**
     * AC-06: Mahasiswa dapat mengakses Career Path tanpa menyelesaikan Career Assessment terlebih dahulu.
     */
    public function test_student_can_access_career_path_without_completing_career_assessment(): void
    {
        // Mahasiswa baru tanpa record assessment apa pun
        $roleMahasiswa = Role::firstOrCreate(['name' => Role::MAHASISWA]);
        $freshStudent = User::factory()->create([
            'role_id' => $roleMahasiswa->id,
            'email' => 'fresh.student@careercompass.test',
        ]);

        $profession = Profession::where('is_active', true)->where('publication_status', 'published')->firstOrFail();

        $response = $this->actingAs($freshStudent, 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));

        $response->assertOk()
            ->assertViewIs('careers.show')
            ->assertSee($profession->name)
            ->assertSee('Jalur Karier');
    }

    /**
     * Profesi tanpa tahapan Career Path menampilkan fallback yang informatif.
     */
    public function test_profession_without_stages_displays_informative_fallback(): void
    {
        $category = CareerCategory::firstOrFail();
        $profession = Profession::create([
            'career_category_id' => $category->id,
            'name' => 'Profesi Kosong Tanpa Tahapan',
            'description' => 'Deskripsi profesi tanpa tahapan.',
            'responsibilities' => ['Tanggung jawab contoh.'],
            'career_start' => null,
            'career_end' => null,
            'is_active' => true,
            'publication_status' => 'published',
        ]);

        $response = $this->actingAs($this->user(), 'web')->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));

        $response->assertOk()
            ->assertSee('Data jalur karier belum tersedia')
            ->assertSee('Informasi jenjang dan tahapan karier akan ditampilkan setelah tersedia.');
    }

    /**
     * Tamu yang belum login dialihkan ke halaman login saat mencoba mengakses Career Path.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $profession = Profession::firstOrFail();

        $response = $this->get(route('careers.show', ['profession' => $profession, 'tab' => 'path']));

        $response->assertRedirect(route('login'));
    }
}

