<?php

namespace Tests\Feature;

use App\Models\CareerPathStage;
use App\Models\Competency;
use App\Models\Profession;

class AdminCareerPathTest extends ApiTestCase
{
    /**
     * AC-01: Admin dapat menambahkan tahapan career path pada profesi tertentu.
     */
    public function test_ac01_admin_can_add_career_path_stage_to_specific_profession(): void
    {
        $profession = Profession::firstOrFail();

        $response = $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => 'Junior Role',
                'description' => 'Mengawali karier dengan bimbingan senior.',
            ]
        );

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Junior Role')
            ->assertJsonPath('data.sort_order', 1);

        $this->assertDatabaseHas('career_path_stages', [
            'profession_id' => $profession->id,
            'title' => 'Junior Role',
            'sort_order' => 1,
        ]);
    }

    public function test_ac01_non_admin_cannot_add_career_path_stage(): void
    {
        $profession = Profession::firstOrFail();
        $payload = [
            'title' => 'Forbidden Stage',
            'description' => 'Tidak boleh disimpan.',
        ];

        // Guest
        $this->post(route('admin.professions.career-paths.store', $profession), $payload)
            ->assertRedirect(route('login'));

        // Mahasiswa & Assessor
        foreach (['mahasiswa', 'assessor'] as $role) {
            $this->actingAs($this->user($role), 'web')
                ->post(route('admin.professions.career-paths.store', $profession), $payload)
                ->assertForbidden();
        }

        $this->assertDatabaseMissing('career_path_stages', [
            'title' => 'Forbidden Stage',
        ]);
    }

    /**
     * AC-02: Admin dapat menghubungkan kompetensi yang diperlukan dengan tahapan karier.
     */
    public function test_ac02_admin_can_associate_competencies_with_career_path_stage(): void
    {
        $profession = Profession::firstOrFail();
        $competencies = Competency::limit(2)->get();
        $competencyIds = $competencies->pluck('id')->all();

        $response = $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => 'Tahap Berkompetensi',
                'description' => 'Deskripsi tahapan yang memiliki prasyarat skill.',
                'competency_ids' => $competencyIds,
            ]
        );

        $response->assertCreated();
        $stageId = $response->json('data.id');

        $this->assertCount(2, $response->json('data.competencies'));
        foreach ($competencyIds as $cid) {
            $this->assertDatabaseHas('career_path_stage_competency', [
                'career_path_stage_id' => $stageId,
                'competency_id' => $cid,
            ]);
        }
    }

    public function test_ac02_stage_can_be_saved_without_competencies(): void
    {
        $profession = Profession::firstOrFail();

        $response = $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => 'Tahap Tanpa Skill Khusus',
                'description' => 'Belum memetakan kompetensi tertentu.',
                'competency_ids' => [],
            ]
        );

        $response->assertCreated();
        $this->assertCount(0, $response->json('data.competencies'));
    }

    /**
     * AC-03: Admin dapat melihat daftar dan urutan tahapan career path berdasarkan profesi.
     */
    public function test_ac03_admin_can_view_list_and_order_of_career_path_stages_by_profession(): void
    {
        $professions = Profession::limit(2)->get();
        $profA = $professions[0];
        $profB = $professions[1];

        // Buat 3 tahapan untuk Profesi A dengan sort_order teratur
        $stageA1 = $profA->careerPathStages()->create(['title' => 'Stage 1', 'description' => 'D1', 'sort_order' => 1]);
        $stageA2 = $profA->careerPathStages()->create(['title' => 'Stage 2', 'description' => 'D2', 'sort_order' => 2]);
        $stageA3 = $profA->careerPathStages()->create(['title' => 'Stage 3', 'description' => 'D3', 'sort_order' => 3]);

        // Buat 1 tahapan untuk Profesi B
        $stageB = $profB->careerPathStages()->create(['title' => 'Stage B', 'description' => 'DB', 'sort_order' => 1]);

        $response = $this->actingAs($this->user('admin'), 'web')->getJson(
            route('admin.professions.career-paths.index', $profA)
        );

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $stageA1->id)
            ->assertJsonPath('data.1.id', $stageA2->id)
            ->assertJsonPath('data.2.id', $stageA3->id);

        // Pastikan tahapan profesi B tidak ikut terbawa
        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertNotContains($stageB->id, $ids);
    }

    /**
     * AC-04: Admin dapat mengubah informasi, urutan tahapan, dan kompetensi yang terkait.
     */
    public function test_ac04_admin_can_update_stage_information_order_and_competencies(): void
    {
        $profession = Profession::firstOrFail();
        $competencies = Competency::limit(3)->get();

        $stage = $profession->careerPathStages()->create([
            'title' => 'Initial Title',
            'description' => 'Initial Desc',
            'sort_order' => 1,
        ]);
        $stage->competencies()->sync([$competencies[0]->id]);

        $response = $this->actingAs($this->user('admin'), 'web')->putJson(
            route('admin.professions.career-paths.update', [$profession, $stage]),
            [
                'title' => 'Updated Title',
                'description' => 'Updated Desc',
                'sort_order' => 5,
                'competency_ids' => [$competencies[1]->id, $competencies[2]->id],
            ]
        );

        $response->assertOk()
            ->assertJsonPath('data.title', 'Updated Title')
            ->assertJsonPath('data.description', 'Updated Desc')
            ->assertJsonPath('data.sort_order', 5);

        $this->assertDatabaseHas('career_path_stages', [
            'id' => $stage->id,
            'title' => 'Updated Title',
            'sort_order' => 5,
        ]);

        $this->assertDatabaseMissing('career_path_stage_competency', [
            'career_path_stage_id' => $stage->id,
            'competency_id' => $competencies[0]->id,
        ]);

        $this->assertDatabaseHas('career_path_stage_competency', [
            'career_path_stage_id' => $stage->id,
            'competency_id' => $competencies[1]->id,
        ]);
    }

    public function test_ac04_admin_can_reorder_stages_using_reorder_endpoint(): void
    {
        $profession = Profession::firstOrFail();
        $s1 = $profession->careerPathStages()->create(['title' => 'S1', 'description' => 'D1', 'sort_order' => 1]);
        $s2 = $profession->careerPathStages()->create(['title' => 'S2', 'description' => 'D2', 'sort_order' => 2]);
        $s3 = $profession->careerPathStages()->create(['title' => 'S3', 'description' => 'D3', 'sort_order' => 3]);

        // Balik urutan menjadi S3 (1), S1 (2), S2 (3)
        $response = $this->actingAs($this->user('admin'), 'web')->putJson(
            route('admin.professions.career-paths.reorder', $profession),
            ['stage_ids' => [$s3->id, $s1->id, $s2->id]]
        );

        $response->assertOk();

        $this->assertSame(1, $s3->fresh()->sort_order);
        $this->assertSame(2, $s1->fresh()->sort_order);
        $this->assertSame(3, $s2->fresh()->sort_order);
    }

    /**
     * AC-05: Admin dapat menghapus tahapan career path yang tidak digunakan.
     */
    public function test_ac05_admin_can_delete_stage_without_deleting_profession_or_competencies(): void
    {
        $profession = Profession::firstOrFail();
        $competency = Competency::firstOrFail();

        $stage = $profession->careerPathStages()->create([
            'title' => 'Stage To Delete',
            'description' => 'Akan dihapus.',
            'sort_order' => 1,
        ]);
        $stage->competencies()->sync([$competency->id]);

        $stageId = $stage->id;
        $professionId = $profession->id;
        $competencyId = $competency->id;

        $response = $this->actingAs($this->user('admin'), 'web')->deleteJson(
            route('admin.professions.career-paths.destroy', [$profession, $stage])
        );

        $response->assertOk();

        // Tahapan dan pivot terhapus
        $this->assertDatabaseMissing('career_path_stages', ['id' => $stageId]);
        $this->assertDatabaseMissing('career_path_stage_competency', ['career_path_stage_id' => $stageId]);

        // Master profesi dan kompetensi tetap utuh
        $this->assertDatabaseHas('professions', ['id' => $professionId]);
        $this->assertDatabaseHas('competencies', ['id' => $competencyId]);
    }

    /**
     * AC-06: Sistem memvalidasi data wajib dan memastikan tahapan terhubung dengan profesi yang tersedia.
     */
    public function test_ac06_validation_rejects_missing_required_fields_and_invalid_competencies(): void
    {
        $profession = Profession::firstOrFail();

        // 1. Validasi data wajib: title & description kosong
        $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => '',
                'description' => '',
            ]
        )->assertUnprocessable()->assertJsonValidationErrors(['title', 'description']);

        // 2. Validasi kompetensi tidak valid
        $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => 'Title',
                'description' => 'Desc',
                'competency_ids' => [99999],
            ]
        )->assertUnprocessable()->assertJsonValidationErrors(['competency_ids.0']);

        // 3. Validasi kompetensi duplikat
        $validId = Competency::firstOrFail()->id;
        $this->actingAs($this->user('admin'), 'web')->postJson(
            route('admin.professions.career-paths.store', $profession),
            [
                'title' => 'Title',
                'description' => 'Desc',
                'competency_ids' => [$validId, $validId],
            ]
        )->assertUnprocessable()->assertJsonValidationErrors(['competency_ids.0']);
    }

    public function test_ac06_stage_must_belong_to_valid_existing_profession(): void
    {
        $profession = Profession::firstOrFail();
        $otherProfession = Profession::where('id', '!=', $profession->id)->firstOrFail();

        $stageOfOther = $otherProfession->careerPathStages()->create([
            'title' => 'Other Stage',
            'description' => 'Desc',
            'sort_order' => 1,
        ]);

        // 1. Mengakses profesi non-existent -> 404
        $this->actingAs($this->user('admin'), 'web')->postJson(
            '/admin/professions/99999/career-paths',
            ['title' => 'T', 'description' => 'D']
        )->assertNotFound();

        // 2. Mengupdate tahapan milik profesi lain lewat route profesi ini -> 404
        $this->actingAs($this->user('admin'), 'web')->putJson(
            route('admin.professions.career-paths.update', [$profession, $stageOfOther]),
            ['title' => 'Hacked Title', 'description' => 'Desc']
        )->assertNotFound();

        // 3. Menghapus tahapan milik profesi lain lewat route profesi ini -> 404
        $this->actingAs($this->user('admin'), 'web')->deleteJson(
            route('admin.professions.career-paths.destroy', [$profession, $stageOfOther])
        )->assertNotFound();

        // 4. Reorder dengan ID tahapan milik profesi lain -> 422
        $stageOfSelf = $profession->careerPathStages()->create([
            'title' => 'Self Stage',
            'description' => 'Desc',
            'sort_order' => 1,
        ]);

        $this->actingAs($this->user('admin'), 'web')->putJson(
            route('admin.professions.career-paths.reorder', $profession),
            ['stage_ids' => [$stageOfSelf->id, $stageOfOther->id]]
        )->assertUnprocessable();
    }

    /**
     * Frontend Integration Tests: Halaman Manajemen Profesi, Detail Profesi, dan View Manage Career Path.
     */
    public function test_admin_can_access_career_path_from_profession_index_and_show_pages(): void
    {
        $profession = Profession::firstOrFail();

        // 1. Pada halaman Manajemen Profesi (index), terdapat akses "Career Path"
        $this->actingAs($this->user('admin'), 'web')->get(route('admin.professions.index'))
            ->assertOk()
            ->assertSee(route('admin.professions.career-paths.index', $profession), false)
            ->assertSee('Career Path');

        // 2. Pada halaman Detail Profesi (show), terdapat tombol "Kelola Career Path"
        $this->actingAs($this->user('admin'), 'web')->get(route('admin.professions.show', $profession))
            ->assertOk()
            ->assertSee(route('admin.professions.career-paths.index', $profession), false)
            ->assertSee('Kelola Career Path');
    }

    public function test_admin_web_view_renders_manage_page_with_stages_and_competencies(): void
    {
        $profession = Profession::firstOrFail();
        $competency = Competency::firstOrFail();

        // Saat tahapan masih kosong: menampilkan empty state
        $this->actingAs($this->user('admin'), 'web')->get(route('admin.professions.career-paths.index', $profession))
            ->assertOk()
            ->assertViewIs('admin.career-paths.manage')
            ->assertSee('Belum Ada Tahapan Karier')
            ->assertSee('Tambah Tahapan');

        // Tambahkan tahapan berkategori & kompetensi
        $stage = $profession->careerPathStages()->create([
            'title' => 'Tahap Pengujian Frontend',
            'description' => 'Deskripsi tahapan untuk memastikan render Blade valid.',
            'sort_order' => 1,
        ]);
        $stage->competencies()->sync([$competency->id]);

        $this->actingAs($this->user('admin'), 'web')->get(route('admin.professions.career-paths.index', $profession))
            ->assertOk()
            ->assertSee('Tahap Pengujian Frontend')
            ->assertSee('Deskripsi tahapan untuk memastikan render Blade valid.')
            ->assertSee($competency->name)
            ->assertSee('Tahap 1');
    }

    public function test_admin_web_form_actions_redirect_with_session_status(): void
    {
        $profession = Profession::firstOrFail();
        $competency = Competency::firstOrFail();

        // Web POST
        $this->actingAs($this->user('admin'), 'web')->from(route('admin.professions.career-paths.index', $profession))
            ->post(route('admin.professions.career-paths.store', $profession), [
                'title' => 'Tahap Baru Web Form',
                'description' => 'Deskripsi tahapan web form.',
                'competency_ids' => [$competency->id],
            ])
            ->assertRedirect(route('admin.professions.career-paths.index', $profession))
            ->assertSessionHas('status', 'Tahapan karier berhasil ditambahkan.');

        $stage = $profession->careerPathStages()->where('title', 'Tahap Baru Web Form')->firstOrFail();

        // Web PUT Update
        $this->actingAs($this->user('admin'), 'web')->from(route('admin.professions.career-paths.index', $profession))
            ->put(route('admin.professions.career-paths.update', [$profession, $stage]), [
                'title' => 'Tahap Diperbarui Web Form',
                'description' => 'Deskripsi tahapan diperbarui.',
            ])
            ->assertRedirect(route('admin.professions.career-paths.index', $profession))
            ->assertSessionHas('status', 'Tahapan karier berhasil diperbarui.');

        // Web PUT Reorder
        $this->actingAs($this->user('admin'), 'web')->from(route('admin.professions.career-paths.index', $profession))
            ->put(route('admin.professions.career-paths.reorder', $profession), [
                'stage_ids' => [$stage->id],
            ])
            ->assertRedirect(route('admin.professions.career-paths.index', $profession))
            ->assertSessionHas('status', 'Urutan tahapan karier berhasil diperbarui.');

        // Web DELETE
        $this->actingAs($this->user('admin'), 'web')->from(route('admin.professions.career-paths.index', $profession))
            ->delete(route('admin.professions.career-paths.destroy', [$profession, $stage]))
            ->assertRedirect(route('admin.professions.career-paths.index', $profession))
            ->assertSessionHas('status', 'Tahapan karier berhasil dihapus.');
    }
}
