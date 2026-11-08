<?php

namespace Tests\Feature;

use App\Models\ActionPlan;
use App\Models\ActionPlanTemplate;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class StudentCareerActionPlanWebTest extends ApiTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Mengikuti Sertifikasi Cloud Practitioner',
            'description' => 'Mempersiapkan materi dan mengikuti ujian sertifikasi cloud resmi.',
            'due_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'status' => 'Not Started',
        ], $overrides);
    }

    /**
     * Skenario 1: Mahasiswa dapat melihat halaman Career Action Plan beserta ringkasan progres.
     */
    public function test_01_mahasiswa_dapat_melihat_halaman_career_action_plan_beserta_ringkasan_progres(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->get('/career-action-plan');

        $response->assertOk();
        $response->assertSee('Career Action Plan');
        $response->assertSee('Total Aktivitas');
        $response->assertSee('Belum Dimulai');
        $response->assertSee('Sedang Berjalan');
        $response->assertSee('Selesai');
        $response->assertSee('Tingkat Penyelesaian Rencana Aksi');
        $response->assertSee('Tambah Aktivitas Pengembangan Diri Mandiri');
    }

    /**
     * Skenario 2: Mahasiswa berhasil menambahkan aktivitas mandiri dengan tenggat waktu dan status.
     */
    public function test_02_mahasiswa_berhasil_menambahkan_aktivitas_mandiri_dengan_tenggat_waktu_dan_status(): void
    {
        $student = $this->user('mahasiswa');
        $dueDate = Carbon::now()->addDays(14)->format('Y-m-d');
        $payload = $this->payload([
            'title' => 'Mengerjakan Proyek Capstone Fullstack',
            'description' => 'Membangun aplikasi web sistem informasi karier menggunakan Laravel.',
            'due_date' => $dueDate,
            'status' => 'In Progress',
        ]);

        $response = $this->actingAs($student, 'web')->post('/career-action-plan', $payload);

        $response->assertRedirect(route('action-plan.index'));
        $response->assertSessionHas('status', 'Aktivitas berhasil ditambahkan ke Career Action Plan Anda.');

        $this->assertDatabaseHas('action_plans', [
            'user_id' => $student->id,
            'template_id' => null,
            'title' => 'Mengerjakan Proyek Capstone Fullstack',
            'description' => 'Membangun aplikasi web sistem informasi karier menggunakan Laravel.',
            'status' => 'In Progress',
        ]);

        $createdPlan = $student->actionPlans()->where('title', 'Mengerjakan Proyek Capstone Fullstack')->firstOrFail();
        $this->assertSame($dueDate, $createdPlan->due_date->format('Y-m-d'));

        $this->actingAs($student, 'web')->get('/career-action-plan')
            ->assertOk()
            ->assertSee('Mengerjakan Proyek Capstone Fullstack')
            ->assertSee('Mandiri');
    }

    /**
     * Skenario 3: Validasi gagal saat menambahkan aktivitas tanpa judul atau hanya spasi.
     */
    public function test_03_validasi_gagal_menambahkan_aktivitas_tanpa_judul_atau_hanya_spasi(): void
    {
        $student = $this->user('mahasiswa');

        // Judul kosong
        $response = $this->actingAs($student, 'web')->from('/career-action-plan')
            ->post('/career-action-plan', $this->payload(['title' => '']));
        $response->assertRedirect('/career-action-plan');
        $response->assertSessionHasErrors(['title']);

        // Judul hanya whitespace
        $response = $this->actingAs($student, 'web')->from('/career-action-plan')
            ->post('/career-action-plan', $this->payload(['title' => '     ']));
        $response->assertRedirect('/career-action-plan');
        $response->assertSessionHasErrors(['title']);

        $this->assertSame(0, $student->actionPlans()->count());
    }

    /**
     * Skenario 4: Validasi menolak status yang tidak sah pada penambahan aktivitas.
     */
    public function test_04_validasi_menolak_status_yang_tidak_sah_pada_penambahan_aktivitas(): void
    {
        $student = $this->user('mahasiswa');

        $response = $this->actingAs($student, 'web')->from('/career-action-plan')
            ->post('/career-action-plan', $this->payload(['status' => 'StatusPalsu']));

        $response->assertRedirect('/career-action-plan');
        $response->assertSessionHasErrors(['status']);

        $this->assertSame(0, $student->actionPlans()->count());
    }

    /**
     * Skenario 5: Mahasiswa dapat membuka halaman edit aktivitas miliknya.
     */
    public function test_05_mahasiswa_dapat_membuka_halaman_edit_aktivitas_miliknya(): void
    {
        $student = $this->user('mahasiswa');
        $plan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Belajar Docker Containerization',
            'description' => 'Membuat dockerfile dan docker-compose.',
            'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'status' => 'Not Started',
        ]);

        $response = $this->actingAs($student, 'web')->get('/career-action-plan/'.$plan->id.'/edit');

        $response->assertOk();
        $response->assertSee('Edit Aktivitas Action Plan');
        $response->assertSee('Belajar Docker Containerization');
        $response->assertSee('Simpan Perubahan');
    }

    /**
     * Skenario 6: Mahasiswa berhasil memperbarui aktivitas pengembangan diri.
     */
    public function test_06_mahasiswa_berhasil_memperbarui_aktivitas_pengembangan_diri(): void
    {
        $student = $this->user('mahasiswa');
        $plan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Judul Awal',
            'description' => 'Deskripsi awal.',
            'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'status' => 'Not Started',
        ]);

        $newDueDate = Carbon::now()->addDays(20)->format('Y-m-d');
        $response = $this->actingAs($student, 'web')->put('/career-action-plan/'.$plan->id, [
            'title' => 'Judul Telah Diperbarui',
            'description' => 'Deskripsi telah diperbarui dengan langkah detail.',
            'due_date' => $newDueDate,
            'status' => 'In Progress',
        ]);

        $response->assertRedirect(route('action-plan.index'));
        $response->assertSessionHas('status', 'Aktivitas berhasil diperbarui.');

        $this->assertDatabaseHas('action_plans', [
            'id' => $plan->id,
            'title' => 'Judul Telah Diperbarui',
            'description' => 'Deskripsi telah diperbarui dengan langkah detail.',
            'status' => 'In Progress',
        ]);

        $this->assertSame($newDueDate, $plan->fresh()->due_date->format('Y-m-d'));
    }

    /**
     * Skenario 7: Mahasiswa berhasil memperbarui status secara cepat via PATCH.
     */
    public function test_07_mahasiswa_berhasil_memperbarui_status_secara_cepat_via_patch(): void
    {
        $student = $this->user('mahasiswa');
        $plan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Mempersiapkan Portofolio GitHub',
            'status' => 'Not Started',
        ]);

        // Ubah ke In Progress
        $response = $this->actingAs($student, 'web')->patch('/career-action-plan/'.$plan->id.'/status', [
            'status' => 'In Progress',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertSame('In Progress', $plan->fresh()->status);

        // Ubah ke Completed
        $response = $this->actingAs($student, 'web')->patch('/career-action-plan/'.$plan->id.'/status', [
            'status' => 'Completed',
        ]);
        $response->assertRedirect();
        $this->assertSame('Completed', $plan->fresh()->status);
    }

    /**
     * Skenario 8: Quick status menolak nilai status tidak valid.
     */
    public function test_08_quick_status_menolak_nilai_status_tidak_valid(): void
    {
        $student = $this->user('mahasiswa');
        $plan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Aktivitas Uji Status',
            'status' => 'Not Started',
        ]);

        $response = $this->actingAs($student, 'web')->from('/career-action-plan')
            ->patch('/career-action-plan/'.$plan->id.'/status', [
                'status' => 'UnknownStatus',
            ]);

        $response->assertRedirect('/career-action-plan');
        $response->assertSessionHasErrors(['status']);
        $this->assertSame('Not Started', $plan->fresh()->status);
    }

    /**
     * Skenario 9: Mahasiswa berhasil menghapus aktivitas miliknya.
     */
    public function test_09_mahasiswa_berhasil_menghapus_aktivitas_miliknya(): void
    {
        $student = $this->user('mahasiswa');
        $plan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Aktivitas yang Akan Dihapus',
            'status' => 'Not Started',
        ]);

        $response = $this->actingAs($student, 'web')->delete('/career-action-plan/'.$plan->id);

        $response->assertRedirect(route('action-plan.index'));
        $response->assertSessionHas('status', 'Aktivitas berhasil dihapus dari Career Action Plan.');

        $this->assertDatabaseMissing('action_plans', ['id' => $plan->id]);
    }

    /**
     * Skenario 10: Mahasiswa tidak dapat melihat atau mengedit aktivitas milik mahasiswa lain (IDOR protection).
     */
    public function test_10_mahasiswa_tidak_dapat_melihat_atau_mengedit_aktivitas_milik_mahasiswa_lain_idor(): void
    {
        $student1 = $this->user('mahasiswa');
        $student2 = User::factory()->create(['role_id' => Role::where('name', 'mahasiswa')->firstOrFail()->id]);

        $plan1 = ActionPlan::create([
            'user_id' => $student1->id,
            'title' => 'Rencana Rahasia Mahasiswa 1',
            'status' => 'Not Started',
        ]);

        // Student 2 mencoba membuka halaman edit milik student 1
        $this->actingAs($student2, 'web')->get('/career-action-plan/'.$plan1->id.'/edit')
            ->assertForbidden();

        // Student 2 mencoba memperbarui aktivitas student 1
        $this->actingAs($student2, 'web')->put('/career-action-plan/'.$plan1->id, [
            'title' => 'Perubahan Ilegal oleh Mahasiswa 2',
            'status' => 'Completed',
        ])->assertForbidden();

        // Student 2 mencoba update status student 1
        $this->actingAs($student2, 'web')->patch('/career-action-plan/'.$plan1->id.'/status', [
            'status' => 'Completed',
        ])->assertForbidden();

        // Data student 1 tidak berubah
        $this->assertSame('Rencana Rahasia Mahasiswa 1', $plan1->fresh()->title);
        $this->assertSame('Not Started', $plan1->fresh()->status);
    }

    /**
     * Skenario 11: Mahasiswa tidak dapat menghapus aktivitas milik mahasiswa lain (IDOR protection).
     */
    public function test_11_mahasiswa_tidak_dapat_menghapus_aktivitas_milik_mahasiswa_lain_idor(): void
    {
        $student1 = $this->user('mahasiswa');
        $student2 = User::factory()->create(['role_id' => Role::where('name', 'mahasiswa')->firstOrFail()->id]);

        $plan1 = ActionPlan::create([
            'user_id' => $student1->id,
            'title' => 'Rencana Aman Mahasiswa 1',
            'status' => 'Not Started',
        ]);

        $this->actingAs($student2, 'web')->delete('/career-action-plan/'.$plan1->id)
            ->assertForbidden();

        $this->assertDatabaseHas('action_plans', ['id' => $plan1->id]);
    }

    /**
     * Skenario 12: Perhitungan ringkasan statistik dan persentase ketercapaian akurat.
     */
    public function test_12_perhitungan_ringkasan_statistik_dan_persentase_ketercapaian_akurat(): void
    {
        $student = $this->user('mahasiswa');

        // Buat 4 aktivitas: 1 Not Started, 1 In Progress, 2 Completed (total 4, 50% selesai)
        ActionPlan::create(['user_id' => $student->id, 'title' => 'Plan 1', 'status' => 'Not Started']);
        ActionPlan::create(['user_id' => $student->id, 'title' => 'Plan 2', 'status' => 'In Progress']);
        ActionPlan::create(['user_id' => $student->id, 'title' => 'Plan 3', 'status' => 'Completed']);
        ActionPlan::create(['user_id' => $student->id, 'title' => 'Plan 4', 'status' => 'Completed']);

        $response = $this->actingAs($student, 'web')->get('/career-action-plan');

        $response->assertOk();
        $response->assertSee('50%');
        $response->assertSee('2 dari 4 aktivitas telah diselesaikan.');
    }

    /**
     * Skenario 13: Indikator overdue ditampilkan ketika tenggat waktu terlewat dan belum selesai.
     */
    public function test_13_indikator_overdue_ditampilkan_ketika_tenggat_waktu_terlewat_dan_belum_selesai(): void
    {
        $student = $this->user('mahasiswa');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Overdue karena belum completed
        $overduePlan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Aktivitas Terlambat',
            'due_date' => $yesterday,
            'status' => 'In Progress',
        ]);

        // Bukan overdue karena sudah completed
        $completedPlan = ActionPlan::create([
            'user_id' => $student->id,
            'title' => 'Aktivitas Tepat Waktu',
            'due_date' => $yesterday,
            'status' => 'Completed',
        ]);

        $this->assertTrue($overduePlan->isOverdue());
        $this->assertFalse($completedPlan->isOverdue());

        $response = $this->actingAs($student, 'web')->get('/career-action-plan');
        $response->assertOk();
        $response->assertSee('Terlewat');
    }

    /**
     * Skenario 14: Filter status dan pencarian berfungsi dengan benar.
     */
    public function test_14_filter_status_dan_pencarian_berfungsi_dengan_benar(): void
    {
        $student = $this->user('mahasiswa');

        ActionPlan::create(['user_id' => $student->id, 'title' => 'Belajar Flutter Dasar', 'status' => 'In Progress']);
        ActionPlan::create(['user_id' => $student->id, 'title' => 'Belajar Golang Microservices', 'status' => 'Completed']);

        // Filter status Completed
        $response = $this->actingAs($student, 'web')->get('/career-action-plan?status=Completed');
        $response->assertOk();
        $response->assertSee('Belajar Golang Microservices');
        $response->assertDontSee('Belajar Flutter Dasar');

        // Pencarian dengan keyword "Flutter"
        $responseSearch = $this->actingAs($student, 'web')->get('/career-action-plan?q=Flutter');
        $responseSearch->assertOk();
        $responseSearch->assertSee('Belajar Flutter Dasar');
        $responseSearch->assertDontSee('Belajar Golang Microservices');
    }

    /**
     * Skenario 15: Non-mahasiswa dan tamu tidak dapat mengakses Career Action Plan mahasiswa.
     */
    public function test_15_non_mahasiswa_dan_tamu_tidak_dapat_mengakses_career_action_plan_mahasiswa(): void
    {
        $admin = $this->user('admin');
        $assessor = $this->user('assessor');

        // Tamu (guest) diarahkan ke login
        $this->get('/career-action-plan')->assertRedirect(route('login'));

        // Admin dan Assessor ditolak (403 Forbidden)
        $this->actingAs($admin, 'web')->get('/career-action-plan')->assertForbidden();
        $this->actingAs($assessor, 'web')->get('/career-action-plan')->assertForbidden();
    }
}
