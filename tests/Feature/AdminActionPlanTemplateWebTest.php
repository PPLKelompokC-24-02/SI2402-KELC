<?php

namespace Tests\Feature;

use App\Models\ActionPlan;
use App\Models\ActionPlanTemplate;
use App\Models\Role;
use App\Models\User;

class AdminActionPlanTemplateWebTest extends ApiTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Mengikuti Bootcamp Web Development',
            'description' => 'Mengikuti bootcamp intensif untuk meningkatkan keahlian fullstack web development.',
            'is_active' => '1',
        ], $overrides);
    }

    /**
     * Skenario 1: Admin berhasil menambahkan template valid.
     */
    public function test_01_admin_berhasil_menambahkan_template_valid(): void
    {
        $admin = $this->user('admin');
        $payload = $this->payload();

        $response = $this->actingAs($admin, 'web')
            ->post('/admin/action-templates', $payload);

        $response->assertRedirect(route('admin.action-templates.index'));
        $response->assertSessionHas('status', 'Template aktivitas berhasil ditambahkan.');

        $this->assertDatabaseHas('action_plan_templates', [
            'title' => 'Mengikuti Bootcamp Web Development',
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'web')->get('/admin/action-templates')
            ->assertOk()
            ->assertSee('Mengikuti Bootcamp Web Development');
    }

    /**
     * Skenario 2: Admin gagal menyimpan template tanpa judul atau hanya spasi.
     */
    public function test_02_admin_gagal_menyimpan_template_tanpa_judul_atau_hanya_spasi(): void
    {
        $admin = $this->user('admin');

        // Empty title
        $response = $this->actingAs($admin, 'web')->from('/admin/action-templates/create')
            ->post('/admin/action-templates', $this->payload(['title' => '']));
        $response->assertRedirect('/admin/action-templates/create');
        $response->assertSessionHasErrors(['title']);

        // Whitespace-only title
        $response = $this->actingAs($admin, 'web')->from('/admin/action-templates/create')
            ->post('/admin/action-templates', $this->payload(['title' => '     ']));
        $response->assertRedirect('/admin/action-templates/create');
        $response->assertSessionHasErrors(['title']);

        // Empty description
        $response = $this->actingAs($admin, 'web')->from('/admin/action-templates/create')
            ->post('/admin/action-templates', $this->payload(['description' => '']));
        $response->assertRedirect('/admin/action-templates/create');
        $response->assertSessionHasErrors(['description']);

        // Whitespace-only description
        $response = $this->actingAs($admin, 'web')->from('/admin/action-templates/create')
            ->post('/admin/action-templates', $this->payload(['description' => "   \t \n   "]));
        $response->assertRedirect('/admin/action-templates/create');
        $response->assertSessionHasErrors(['description']);

        // Title exceeding 255 chars
        $response = $this->actingAs($admin, 'web')->from('/admin/action-templates/create')
            ->post('/admin/action-templates', $this->payload(['title' => str_repeat('A', 256)]));
        $response->assertRedirect('/admin/action-templates/create');
        $response->assertSessionHasErrors(['title']);

        $this->assertDatabaseMissing('action_plan_templates', [
            'description' => $this->payload()['description'],
        ]);
    }

    /**
     * Skenario 3: Admin dapat melihat daftar dan detail template.
     */
    public function test_03_admin_dapat_melihat_daftar_dan_detail_template(): void
    {
        $admin = $this->user('admin');
        $template = ActionPlanTemplate::firstOrFail();

        // Daftar template
        $response = $this->actingAs($admin, 'web')->get('/admin/action-templates');
        $response->assertOk();
        $response->assertSee('Template Aktivitas');
        $response->assertSee($template->title);
        $response->assertSee('Aktif');

        // Detail template
        $detail = $this->actingAs($admin, 'web')->get('/admin/action-templates/'.$template->id);
        $detail->assertOk();
        $detail->assertSee($template->title);
        $detail->assertSee($template->description);
        $detail->assertSee('Detail Template Aktivitas');
        $detail->assertSee('Integritas Data Mahasiswa');

        // Empty state saat filter pencarian tidak cocok
        $emptySearch = $this->actingAs($admin, 'web')->get('/admin/action-templates?keyword=KATA_KUNCI_TIDAK_MUNGKIN_ADA_XYZ');
        $emptySearch->assertOk();
        $emptySearch->assertSee('Tidak ada template yang cocok');
    }

    /**
     * Skenario 4: Admin berhasil mengubah template.
     */
    public function test_04_admin_berhasil_mengubah_template(): void
    {
        $admin = $this->user('admin');
        $template = ActionPlanTemplate::firstOrFail();

        $response = $this->actingAs($admin, 'web')->put('/admin/action-templates/'.$template->id, [
            'title' => 'Judul Template Baru Diperbarui',
            'description' => 'Deskripsi template baru yang telah disesuaikan oleh admin.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.action-templates.show', $template));
        $response->assertSessionHas('status', 'Template aktivitas berhasil diperbarui.');

        $this->assertDatabaseHas('action_plan_templates', [
            'id' => $template->id,
            'title' => 'Judul Template Baru Diperbarui',
            'description' => 'Deskripsi template baru yang telah disesuaikan oleh admin.',
        ]);

        $this->actingAs($admin, 'web')->get('/admin/action-templates/'.$template->id)
            ->assertSee('Judul Template Baru Diperbarui');
    }

    /**
     * Skenario 5: Admin dapat menonaktifkan template.
     */
    public function test_05_admin_dapat_menonaktifkan_template(): void
    {
        $admin = $this->user('admin');
        $template = ActionPlanTemplate::firstOrFail();

        // Nonaktifkan via DELETE dengan action=deactivate
        $response = $this->actingAs($admin, 'web')
            ->delete('/admin/action-templates/'.$template->id, ['action' => 'deactivate']);

        $response->assertRedirect(route('admin.action-templates.index'));
        $this->assertFalse($template->fresh()->is_active);

        // Muncul pada filter nonaktif
        $this->actingAs($admin, 'web')->get('/admin/action-templates?status=inactive')
            ->assertOk()
            ->assertSee($template->title);

        // Dapat diaktifkan kembali via toggle
        $this->actingAs($admin, 'web')->post('/admin/action-templates/'.$template->id.'/toggle-status')
            ->assertRedirect();
        $this->assertTrue($template->fresh()->is_active);
    }

    /**
     * Skenario 6: Admin dapat menghapus template.
     */
    public function test_06_admin_dapat_menghapus_template(): void
    {
        $admin = $this->user('admin');
        $template = ActionPlanTemplate::create([
            'title' => 'Template Khusus Untuk Dihapus',
            'description' => 'Deskripsi template yang akan dihapus permanen oleh admin.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'web')
            ->delete('/admin/action-templates/'.$template->id, ['action' => 'delete']);

        $response->assertRedirect(route('admin.action-templates.index'));
        $response->assertSessionHas('status', 'Template aktivitas berhasil dihapus.');
        $this->assertDatabaseMissing('action_plan_templates', ['id' => $template->id]);
    }

    /**
     * Skenario 7: Template aktif tersedia bagi mahasiswa.
     */
    public function test_07_template_aktif_tersedia_bagi_mahasiswa(): void
    {
        $student = $this->user('mahasiswa');
        $activeTemplate = ActionPlanTemplate::where('is_active', true)->firstOrFail();

        $response = $this->actingAs($student, 'web')->get('/career-action-plan');
        $response->assertOk();
        $response->assertSee('Career Action Plan');
        $response->assertSee('Referensi Template Aktivitas');
        $response->assertSee($activeTemplate->title);
        $response->assertSee('Gunakan Template');
    }

    /**
     * Skenario 8: Template nonaktif tidak tersedia untuk penggunaan baru.
     */
    public function test_08_template_nonaktif_tidak_tersedia_untuk_penggunaan_baru(): void
    {
        $student = $this->user('mahasiswa');
        $inactiveTemplate = ActionPlanTemplate::create([
            'title' => 'Template Rahasia Nonaktif',
            'description' => 'Deskripsi template nonaktif yang tidak boleh dilihat mahasiswa.',
            'is_active' => false,
        ]);

        $response = $this->actingAs($student, 'web')->get('/career-action-plan');
        $response->assertOk();
        $response->assertDontSee($inactiveTemplate->title);

        // Mencoba menggunakan template nonaktif langsung via POST menghasilkan 404
        $this->actingAs($student, 'web')
            ->post('/career-action-plan/from-template/'.$inactiveTemplate->id)
            ->assertNotFound();
    }

    /**
     * Skenario 9: Mahasiswa dapat membuat aktivitas berdasarkan template dengan data judul dan deskripsi yang disalin.
     */
    public function test_09_mahasiswa_dapat_membuat_aktivitas_berdasarkan_template_dengan_data_disalin(): void
    {
        $student = $this->user('mahasiswa');
        $template = ActionPlanTemplate::create([
            'title' => 'Belajar Analisis Data dengan Python',
            'description' => 'Menyelesaikan modul analisis data dan visualisasi.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student, 'web')
            ->post('/career-action-plan/from-template/'.$template->id);

        $response->assertRedirect(route('action-plan.index'));
        $response->assertSessionHas('status', 'Aktivitas berhasil ditambahkan ke Career Action Plan Anda dari template.');

        $this->assertDatabaseHas('action_plans', [
            'user_id' => $student->id,
            'template_id' => $template->id,
            'title' => 'Belajar Analisis Data dengan Python',
            'description' => 'Menyelesaikan modul analisis data dan visualisasi.',
            'status' => 'Not Started',
        ]);
    }

    /**
     * Skenario 10: Perubahan template tidak mengubah aktivitas mahasiswa yang sudah dibuat.
     */
    public function test_10_perubahan_template_tidak_mengubah_aktivitas_mahasiswa_yang_sudah_dibuat(): void
    {
        $student = $this->user('mahasiswa');
        $template = ActionPlanTemplate::create([
            'title' => 'Belajar SQL Dasar',
            'description' => 'Menyelesaikan modul SQL dasar.',
            'is_active' => true,
        ]);

        $this->actingAs($student, 'web')->post('/career-action-plan/from-template/'.$template->id);
        $actionPlan = ActionPlan::where('template_id', $template->id)->firstOrFail();

        // Admin mengubah template
        $admin = $this->user('admin');
        $this->actingAs($admin, 'web')->put('/admin/action-templates/'.$template->id, [
            'title' => 'Belajar SQL Lanjutan dan Optimasi Database',
            'description' => 'Deskripsi master telah diubah total oleh admin.',
            'is_active' => '1',
        ]);

        // Verifikasi template master berubah
        $this->assertSame('Belajar SQL Lanjutan dan Optimasi Database', $template->fresh()->title);

        // Verifikasi aktivitas mahasiswa TETAP mempertahankan data awal yang disalin
        $this->assertSame('Belajar SQL Dasar', $actionPlan->fresh()->title);
        $this->assertSame('Menyelesaikan modul SQL dasar.', $actionPlan->fresh()->description);
    }

    /**
     * Skenario 11: Penghapusan template tidak menghapus aktivitas mahasiswa yang sudah dibuat.
     */
    public function test_11_penghapusan_template_tidak_menghapus_aktivitas_mahasiswa_yang_sudah_dibuat(): void
    {
        $student = $this->user('mahasiswa');
        $template = ActionPlanTemplate::create([
            'title' => 'Mengikuti Kursus Data Analysis',
            'description' => 'Mengikuti kursus untuk meningkatkan kompetensi analisis data.',
            'is_active' => true,
        ]);

        $this->actingAs($student, 'web')
            ->post('/career-action-plan/from-template/'.$template->id);

        $actionPlan = ActionPlan::where('template_id', $template->id)->firstOrFail();

        // Admin menghapus template
        $admin = $this->user('admin');
        $this->actingAs($admin, 'web')->delete('/admin/action-templates/'.$template->id, ['action' => 'delete']);

        // Template terhapus
        $this->assertDatabaseMissing('action_plan_templates', ['id' => $template->id]);

        // Aktivitas mahasiswa HARUS TETAP ADA di database
        $this->assertDatabaseHas('action_plans', [
            'id' => $actionPlan->id,
            'user_id' => $student->id,
            'title' => 'Mengikuti Kursus Data Analysis',
            'description' => 'Mengikuti kursus untuk meningkatkan kompetensi analisis data.',
            'status' => 'Not Started',
        ]);

        // Mahasiswa tetap dapat melihat aktivitasnya di halaman Career Action Plan
        $this->actingAs($student, 'web')->get('/career-action-plan')
            ->assertOk()
            ->assertSee('Mengikuti Kursus Data Analysis');
    }

    /**
     * Skenario 12: Relasi aktivitas ke template menjadi NULL setelah template dihapus.
     */
    public function test_12_relasi_aktivitas_ke_template_menjadi_null_setelah_template_dihapus(): void
    {
        $student = $this->user('mahasiswa');
        $template = ActionPlanTemplate::create([
            'title' => 'Membangun Portofolio Web',
            'description' => 'Membuat project nyata untuk portofolio.',
            'is_active' => true,
        ]);

        $this->actingAs($student, 'web')
            ->post('/career-action-plan/from-template/'.$template->id);

        $actionPlan = ActionPlan::where('template_id', $template->id)->firstOrFail();
        $this->assertSame($template->id, $actionPlan->template_id);

        // Hapus template
        $template->delete();

        // Relasi template_id otomatis menjadi NULL sesuai ON DELETE SET NULL
        $this->assertNull($actionPlan->fresh()->template_id);
        $this->assertSame('Membangun Portofolio Web', $actionPlan->fresh()->title);
        $this->assertSame('Membuat project nyata untuk portofolio.', $actionPlan->fresh()->description);
    }

    /**
     * Skenario 13: Mahasiswa tidak dapat melakukan CRUD master template.
     */
    public function test_13_mahasiswa_tidak_dapat_melakukan_crud_master_template(): void
    {
        $student = $this->user('mahasiswa');
        $template = ActionPlanTemplate::firstOrFail();

        $this->actingAs($student, 'web')->get('/admin/action-templates')->assertForbidden();
        $this->actingAs($student, 'web')->get('/admin/action-templates/create')->assertForbidden();
        $this->actingAs($student, 'web')->get('/admin/action-templates/'.$template->id)->assertForbidden();
        $this->actingAs($student, 'web')->get('/admin/action-templates/'.$template->id.'/edit')->assertForbidden();
        $this->actingAs($student, 'web')->post('/admin/action-templates', $this->payload())->assertForbidden();
        $this->actingAs($student, 'web')->put('/admin/action-templates/'.$template->id, $this->payload())->assertForbidden();
        $this->actingAs($student, 'web')->delete('/admin/action-templates/'.$template->id)->assertForbidden();
    }

    /**
     * Skenario 14: Assessor tidak dapat melakukan CRUD master template.
     */
    public function test_14_assessor_tidak_dapat_melakukan_crud_master_template(): void
    {
        $assessor = $this->user('assessor');
        $template = ActionPlanTemplate::firstOrFail();

        $this->actingAs($assessor, 'web')->get('/admin/action-templates')->assertForbidden();
        $this->actingAs($assessor, 'web')->get('/admin/action-templates/create')->assertForbidden();
        $this->actingAs($assessor, 'web')->get('/admin/action-templates/'.$template->id)->assertForbidden();
        $this->actingAs($assessor, 'web')->get('/admin/action-templates/'.$template->id.'/edit')->assertForbidden();
        $this->actingAs($assessor, 'web')->post('/admin/action-templates', $this->payload())->assertForbidden();
        $this->actingAs($assessor, 'web')->put('/admin/action-templates/'.$template->id, $this->payload())->assertForbidden();
        $this->actingAs($assessor, 'web')->delete('/admin/action-templates/'.$template->id)->assertForbidden();
    }

    /**
     * Test Tambahan: Mahasiswa hanya dapat mengelola action plan miliknya sendiri.
     */
    public function test_student_can_only_access_own_action_plans(): void
    {
        $student1 = $this->user('mahasiswa');
        $student2 = User::factory()->create(['role_id' => Role::where('name', 'mahasiswa')->firstOrFail()->id]);

        $plan1 = ActionPlan::create([
            'user_id' => $student1->id,
            'title' => 'Aktivitas Rahasia Mahasiswa 1',
            'description' => 'Hanya boleh dilihat oleh mahasiswa 1.',
            'status' => 'Not Started',
        ]);

        $plan2 = ActionPlan::create([
            'user_id' => $student2->id,
            'title' => 'Aktivitas Pribadi Mahasiswa 2',
            'description' => 'Hanya boleh dilihat oleh mahasiswa 2.',
            'status' => 'Not Started',
        ]);

        // Mahasiswa 1 melihat plan miliknya tapi tidak melihat milik mahasiswa 2
        $this->actingAs($student1, 'web')->get('/career-action-plan')
            ->assertOk()
            ->assertSee('Aktivitas Rahasia Mahasiswa 1')
            ->assertDontSee('Aktivitas Pribadi Mahasiswa 2');

        // Mahasiswa 1 tidak bisa menghapus plan mahasiswa 2
        $this->actingAs($student1, 'web')->delete('/career-action-plan/'.$plan2->id)
            ->assertForbidden();

        $this->assertDatabaseHas('action_plans', ['id' => $plan2->id]);

        // Mahasiswa 1 dapat menghapus plan miliknya sendiri
        $this->actingAs($student1, 'web')->delete('/career-action-plan/'.$plan1->id)
            ->assertRedirect(route('action-plan.index'));

        $this->assertDatabaseMissing('action_plans', ['id' => $plan1->id]);
    }
}
