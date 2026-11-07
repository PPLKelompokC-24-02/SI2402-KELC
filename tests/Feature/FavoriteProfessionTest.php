<?php

namespace Tests\Feature;

use App\Models\Profession;
use App\Models\SavedProfession;
use App\Models\User;

class FavoriteProfessionTest extends ApiTestCase
{
    protected Profession $profession1;

    protected Profession $profession2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profession1 = Profession::where('is_active', true)
            ->where('publication_status', 'published')
            ->firstOrFail();

        $this->profession2 = Profession::where('is_active', true)
            ->where('publication_status', 'published')
            ->where('id', '!=', $this->profession1->id)
            ->firstOrFail();
    }

    protected function createStudent(string $email = 'student2@example.com'): User
    {
        return User::factory()->create(['email' => $email]);
    }

    public function test_student_can_save_profession_to_favorites_via_post_and_toggle(): void
    {
        $student = $this->user('mahasiswa');

        // Test POST store
        $response = $this->actingAs($student, 'web')
            ->post(route('careers.favorite.store', $this->profession1));

        $response->assertRedirect();
        $this->assertDatabaseHas('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);

        // Test Toggle (should remove)
        $toggleResponse = $this->actingAs($student, 'web')
            ->post(route('careers.favorite.toggle', $this->profession1));

        $toggleResponse->assertRedirect();
        $this->assertDatabaseMissing('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);

        // Test Toggle again (should add back)
        $toggleAgain = $this->actingAs($student, 'web')
            ->post(route('careers.favorite.toggle', $this->profession1));

        $toggleAgain->assertRedirect();
        $this->assertDatabaseHas('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);
    }

    public function test_saved_profession_is_persisted_in_database_with_relationships(): void
    {
        $student = $this->user('mahasiswa');

        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        $this->assertDatabaseHas('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);

        $savedPivot = SavedProfession::where('user_id', $student->id)
            ->where('profession_id', $this->profession1->id)
            ->first();

        $this->assertNotNull($savedPivot);
        $this->assertSame($student->id, $savedPivot->user->id);
        $this->assertSame($this->profession1->id, $savedPivot->profession->id);

        $this->assertTrue($student->hasSavedProfession($this->profession1));
        $this->assertTrue($this->profession1->isSavedBy($student));
    }

    public function test_student_can_view_favorites_tab_and_saved_professions_list(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        $response = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $response->assertOk()
            ->assertViewIs('careers.professions')
            ->assertSee('Favorit Saya')
            ->assertSee($this->profession1->name)
            ->assertSee('Profesi yang telah kamu simpan sebagai referensi target karier.');
    }

    public function test_tab_favorit_saya_displays_only_saved_professions(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        $response = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $response->assertOk()
            ->assertSee($this->profession1->name)
            ->assertDontSee($this->profession2->name);
    }

    public function test_student_can_delete_profession_from_favorites(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        $response = $this->actingAs($student, 'web')
            ->delete(route('careers.favorite.destroy', $this->profession1));

        $response->assertRedirect();
        $this->assertDatabaseMissing('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);
    }

    public function test_deleting_favorite_does_not_delete_master_profession(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        $this->actingAs($student, 'web')
            ->delete(route('careers.favorite.destroy', $this->profession1));

        $this->assertDatabaseMissing('saved_professions', [
            'user_id' => $student->id,
            'profession_id' => $this->profession1->id,
        ]);

        $this->assertDatabaseHas('professions', [
            'id' => $this->profession1->id,
            'name' => $this->profession1->name,
        ]);
    }

    public function test_system_prevents_duplicate_favorites(): void
    {
        $student = $this->user('mahasiswa');

        // First save
        $this->actingAs($student, 'web')
            ->post(route('careers.favorite.store', $this->profession1));

        $countBefore = SavedProfession::where('user_id', $student->id)
            ->where('profession_id', $this->profession1->id)
            ->count();
        $this->assertSame(1, $countBefore);

        // Attempt duplicate save via web
        $response = $this->actingAs($student, 'web')
            ->post(route('careers.favorite.store', $this->profession1));

        $countAfter = SavedProfession::where('user_id', $student->id)
            ->where('profession_id', $this->profession1->id)
            ->count();
        $this->assertSame(1, $countAfter);
        $response->assertSessionHas('info', 'Profesi sudah menjadi favorit.');

        // Attempt duplicate save via JSON/AJAX
        $jsonResponse = $this->actingAs($student, 'web')
            ->postJson(route('careers.favorite.store', $this->profession1));

        $jsonResponse->assertOk()
            ->assertJson([
                'success' => false,
                'saved' => true,
                'message' => 'Profesi sudah menjadi favorit.',
            ]);
    }

    public function test_heart_icon_status_is_consistent_in_view_and_persists(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        // When viewing catalog
        $response = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'all']));

        $response->assertOk();
        $this->assertStringContainsString('is-active', $response->getContent());

        // Detail view has hero button active
        $detailResponse = $this->actingAs($student, 'web')
            ->get(route('careers.show', $this->profession1));

        $detailResponse->assertOk()
            ->assertSee('Tersimpan di Favorit');

        // Detail view for unsaved profession shows outline / not saved
        $unsavedResponse = $this->actingAs($student, 'web')
            ->get(route('careers.show', $this->profession2));

        $unsavedResponse->assertOk()
            ->assertSee('Simpan ke Favorit');
    }

    public function test_student_a_cannot_view_student_b_favorites(): void
    {
        $studentA = $this->user('mahasiswa');
        $studentB = $this->createStudent('student_b@careercompass.test');

        $studentA->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);
        $studentB->savedProfessions()->syncWithoutDetaching([$this->profession2->id]);

        // Student A visits favorites tab
        $responseA = $this->actingAs($studentA, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $responseA->assertOk()
            ->assertSee($this->profession1->name)
            ->assertDontSee($this->profession2->name);

        // Student B visits favorites tab
        $responseB = $this->actingAs($studentB, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $responseB->assertOk()
            ->assertSee($this->profession2->name)
            ->assertDontSee($this->profession1->name);
    }

    public function test_student_a_cannot_delete_student_b_favorites_pivot(): void
    {
        $studentA = $this->user('mahasiswa');
        $studentB = $this->createStudent('student_b@careercompass.test');

        $studentB->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);
        $pivotB = SavedProfession::where('user_id', $studentB->id)->firstOrFail();

        // Student A tries to delete Student B's saved profession pivot
        $response = $this->actingAs($studentA, 'web')
            ->delete(route('careers.saved-professions.destroy', $pivotB));

        $response->assertForbidden();

        // Check pivot still exists in database
        $this->assertDatabaseHas('saved_professions', [
            'id' => $pivotB->id,
            'user_id' => $studentB->id,
        ]);
    }

    public function test_empty_state_displayed_when_student_has_no_favorites(): void
    {
        $student = $this->user('mahasiswa');
        // Ensure student has 0 favorites
        $student->savedProfessions()->detach();

        $response = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $response->assertOk()
            ->assertSee('Belum ada profesi favorit.')
            ->assertSee('Jelajahi daftar profesi dan simpan profesi yang menarik minatmu.')
            ->assertSee(route('careers.professions.index', ['tab' => 'all']));
    }

    public function test_student_can_use_favorite_feature_without_career_assessment(): void
    {
        $newStudent = $this->createStudent('fresh_student@careercompass.test');

        // Check that student has no assessments
        $response = $this->actingAs($newStudent, 'web')
            ->post(route('careers.favorite.store', $this->profession1));

        $response->assertRedirect();
        $this->assertTrue($newStudent->hasSavedProfession($this->profession1));

        $viewResponse = $this->actingAs($newStudent, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));

        $viewResponse->assertOk()
            ->assertSee($this->profession1->name);
    }

    public function test_non_student_and_guest_cannot_access_favorite_endpoints(): void
    {
        // Guest
        $this->post(route('careers.favorite.store', $this->profession1))
            ->assertRedirect(route('login'));

        $this->delete(route('careers.favorite.destroy', $this->profession1))
            ->assertRedirect(route('login'));

        // Admin
        $admin = $this->user('admin');
        $this->actingAs($admin, 'web')
            ->post(route('careers.favorite.store', $this->profession1))
            ->assertForbidden();

        $this->actingAs($admin, 'web')
            ->delete(route('careers.favorite.destroy', $this->profession1))
            ->assertForbidden();

        // Assessor
        $assessor = $this->user('assessor');
        $this->actingAs($assessor, 'web')
            ->post(route('careers.favorite.store', $this->profession1))
            ->assertForbidden();

        $this->actingAs($assessor, 'web')
            ->delete(route('careers.favorite.destroy', $this->profession1))
            ->assertForbidden();
    }

    public function test_favorite_status_is_consistent_across_catalog_detail_and_favorites_tab(): void
    {
        $student = $this->user('mahasiswa');
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);

        // 1. Catalog shows saved
        $catalogResponse = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'all']));
        $catalogResponse->assertOk();
        $this->assertStringContainsString('is-active', $catalogResponse->getContent());

        // 2. Detail shows saved
        $detailResponse = $this->actingAs($student, 'web')
            ->get(route('careers.show', $this->profession1));
        $detailResponse->assertOk()->assertSee('Tersimpan di Favorit');

        // 3. Favorites tab lists profession
        $favoritesResponse = $this->actingAs($student, 'web')
            ->get(route('careers.professions.index', ['tab' => 'favorites']));
        $favoritesResponse->assertOk()->assertSee($this->profession1->name);
    }

    public function test_ajax_json_requests_return_proper_json_payloads_and_messages(): void
    {
        $student = $this->user('mahasiswa');

        // AJAX Toggle ON
        $toggleOn = $this->actingAs($student, 'web')
            ->postJson(route('careers.favorite.toggle', $this->profession1));

        $toggleOn->assertOk()
            ->assertJson([
                'success' => true,
                'saved' => true,
                'message' => 'Profesi berhasil ditambahkan ke Favorit Saya.',
            ]);

        // AJAX Toggle OFF
        $toggleOff = $this->actingAs($student, 'web')
            ->postJson(route('careers.favorite.toggle', $this->profession1));

        $toggleOff->assertOk()
            ->assertJson([
                'success' => true,
                'saved' => false,
                'message' => 'Profesi berhasil dihapus dari Favorit Saya.',
            ]);

        // AJAX Destroy
        $student->savedProfessions()->syncWithoutDetaching([$this->profession1->id]);
        $destroyJson = $this->actingAs($student, 'web')
            ->deleteJson(route('careers.favorite.destroy', $this->profession1));

        $destroyJson->assertOk()
            ->assertJson([
                'success' => true,
                'saved' => false,
                'message' => 'Profesi berhasil dihapus dari Favorit Saya.',
            ]);
    }
}
