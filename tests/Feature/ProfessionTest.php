<?php

namespace Tests\Feature;

use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Profession;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\DataProvider;

class ProfessionTest extends ApiTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Quality Assurance Engineer',
            'description' => 'Memastikan kualitas aplikasi melalui pengujian.',
            'responsibilities' => ['Menyusun test plan.', 'Melakukan pengujian regresi.'],
            'career_category_id' => CareerCategory::firstOrFail()->id,
            'competency_ids' => Competency::orderBy('id')->limit(2)->pluck('id')->all(),
        ], $overrides);
    }

    public function test_admin_can_create_view_and_update_profession_with_relationships(): void
    {
        $created = $this->authenticate('admin')->postJson('/api/admin/professions', $this->payload());
        $created->assertCreated()->assertJsonPath('data.name', 'Quality Assurance Engineer')->assertJsonCount(2, 'data.competencies')->assertJsonPath('data.is_active', true);
        $id = $created->json('data.id');
        $this->getJson('/api/admin/professions/'.$id)->assertOk()->assertJsonStructure(['data' => ['career_category' => ['id', 'name'], 'competencies', 'responsibilities']]);
        $competency = Competency::orderByDesc('id')->firstOrFail();
        $this->putJson('/api/admin/professions/'.$id, $this->payload(['name' => 'QA Engineer', 'competency_ids' => [$competency->id]]))
            ->assertOk()->assertJsonPath('data.name', 'QA Engineer')->assertJsonCount(1, 'data.competencies')->assertJsonPath('data.competencies.0.id', $competency->id);
        $this->assertDatabaseCount('professions', 6);
        $this->assertSame(1, Profession::findOrFail($id)->competencies()->count());
    }

    public function test_admin_can_list_active_and_inactive_professions(): void
    {
        Profession::firstOrFail()->update(['is_active' => false]);
        $this->authenticate('admin')->getJson('/api/admin/professions?per_page=2')->assertOk()->assertJsonCount(2, 'data')->assertJsonPath('meta.total', 5);
        $this->getJson('/api/admin/professions?keyword=Data')->assertOk()->assertJsonPath('data.0.name', 'Data Analyst')->assertJsonPath('meta.total', 1);
    }

    public function test_profession_requires_all_mandatory_data_and_existing_master_ids(): void
    {
        $this->authenticate('admin')->postJson('/api/admin/professions', [])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'description', 'responsibilities', 'career_category_id', 'competency_ids']);
        $this->postJson('/api/admin/professions', $this->payload(['name' => 'Data Analyst', 'career_category_id' => 99999, 'competency_ids' => [99999], 'responsibilities' => []]))
            ->assertUnprocessable()->assertJsonValidationErrors(['name', 'career_category_id', 'competency_ids.0', 'responsibilities']);
        $this->assertDatabaseCount('professions', 5);
    }

    public function test_profession_rejects_duplicate_competencies_and_invalid_nested_values(): void
    {
        $id = Competency::firstOrFail()->id;
        $this->authenticate('admin')->postJson('/api/admin/professions', $this->payload(['competency_ids' => [$id, $id], 'responsibilities' => [['invalid']], 'is_active' => 'maybe']))
            ->assertUnprocessable()->assertJsonValidationErrors(['competency_ids.0', 'responsibilities.0', 'is_active']);
    }

    public function test_patch_preserves_omitted_relationships_and_put_requires_full_payload(): void
    {
        $profession = Profession::firstOrFail();
        $before = $profession->competencies()->pluck('competencies.id')->all();
        $this->authenticate('admin')->patchJson('/api/admin/professions/'.$profession->id, ['description' => 'Deskripsi baru.'])
            ->assertOk()->assertJsonPath('data.description', 'Deskripsi baru.');
        $this->assertSame($before, $profession->competencies()->pluck('competencies.id')->all());
        $this->putJson('/api/admin/professions/'.$profession->id, ['description' => 'Tidak lengkap.'])->assertUnprocessable()->assertJsonValidationErrors('name');
    }

    public function test_deactivation_preserves_record_and_competencies_and_can_be_reversed(): void
    {
        $profession = Profession::firstOrFail();
        $competencies = $profession->competencies()->count();
        $this->authenticate('admin')->deleteJson('/api/admin/professions/'.$profession->id)->assertOk();
        $this->assertDatabaseHas('professions', ['id' => $profession->id, 'is_active' => false]);
        $this->assertSame($competencies, $profession->competencies()->count());
        $this->getJson('/api/admin/professions/'.$profession->id)->assertOk();
        $this->authenticate()->getJson('/api/professions/'.$profession->id)->assertNotFound();
        $this->getJson('/api/professions')->assertOk()->assertJsonPath('meta.total', 4);
        $this->authenticate('admin')->patchJson('/api/admin/professions/'.$profession->id, ['is_active' => true])->assertOk();
        $this->authenticate()->getJson('/api/professions/'.$profession->id)->assertOk();
    }

    public function test_student_can_explore_paginated_catalog_and_detail_without_assessment(): void
    {
        $this->authenticate()->getJson('/api/professions?per_page=2&page=2')->assertOk()
            ->assertJsonCount(2, 'data')->assertJsonPath('meta.total', 5)->assertJsonPath('meta.current_page', 2)->assertJsonStructure(['links', 'meta']);
        $profession = Profession::where('name', 'Data Analyst')->firstOrFail();
        $this->getJson('/api/professions/'.$profession->id)->assertOk()->assertJsonPath('data.name', 'Data Analyst')
            ->assertJsonStructure(['data' => ['description', 'responsibilities', 'career_category' => ['id', 'name', 'description'], 'competencies' => [['id', 'name', 'description']]]]);
    }

    public function test_search_matches_name_case_insensitively_and_empty_result_has_message(): void
    {
        $this->authenticate()->getJson('/api/professions?keyword=analyst')->assertOk()->assertJsonCount(3, 'data');
        $this->getJson('/api/professions?keyword=DATA')->assertOk()->assertJsonPath('data.0.name', 'Data Analyst');
        $this->getJson('/api/professions?keyword=tidak-ada')->assertOk()->assertJsonPath('data', [])->assertJsonPath('meta.total', 0)->assertJsonPath('message', 'Tidak ada profesi yang ditemukan.');
        $this->getJson('/api/professions?keyword='.urlencode("' OR 1=1 --"))->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_catalog_validates_query_parameters_and_missing_ids(): void
    {
        $this->authenticate()->getJson('/api/professions?per_page=101&page=0&keyword[]=x')->assertUnprocessable()->assertJsonValidationErrors(['per_page', 'page', 'keyword']);
        $this->getJson('/api/professions/99999')->assertNotFound();
        $this->authenticate('admin')->getJson('/api/admin/professions/99999')->assertNotFound();
    }

    public static function nonAdmins(): array
    {
        return [['mahasiswa'], ['assessor']];
    }

    #[DataProvider('nonAdmins')]
    public function test_non_admins_cannot_access_any_admin_catalog_or_master_endpoint(string $role): void
    {
        $id = Profession::firstOrFail()->id;
        $this->authenticate($role);
        foreach (['/api/admin/professions', '/api/admin/professions/'.$id, '/api/admin/career-categories', '/api/admin/competencies', '/api/admin/roles', '/api/admin/users'] as $url) {
            $this->getJson($url)->assertForbidden();
        }
        $this->postJson('/api/admin/professions', $this->payload())->assertForbidden();
        $this->putJson('/api/admin/professions/'.$id, $this->payload())->assertForbidden();
        $this->patchJson('/api/admin/professions/'.$id, ['is_active' => false])->assertForbidden();
        $this->deleteJson('/api/admin/professions/'.$id)->assertForbidden();
        $this->assertDatabaseCount('professions', 5);
        $this->assertTrue(Profession::findOrFail($id)->is_active);
    }

    public function test_profession_policies_deny_student_and_assessor_writes_independently_of_routes(): void
    {
        $profession = Profession::firstOrFail();
        foreach (['mahasiswa', 'assessor'] as $role) {
            $gate = Gate::forUser($this->user($role));
            $this->assertFalse($gate->allows('create', Profession::class));
            $this->assertFalse($gate->allows('update', $profession));
            $this->assertFalse($gate->allows('delete', $profession));
        }
    }

    public function test_admin_can_read_master_data_for_profession_forms(): void
    {
        $this->authenticate('admin')->getJson('/api/admin/career-categories')->assertOk()->assertJsonCount(4, 'data');
        $this->getJson('/api/admin/competencies')->assertOk()->assertJsonCount(16, 'data');
        $this->getJson('/api/admin/roles')->assertOk()->assertJsonCount(3, 'data');
    }
}
