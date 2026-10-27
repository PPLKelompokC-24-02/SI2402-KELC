<?php

namespace Tests\Feature;

use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Profession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProfessionWebTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'action' => 'publish', 'name' => 'Quality Assurance Engineer',
            'career_category_id' => CareerCategory::firstOrFail()->id,
            'summary' => 'Memastikan kualitas dan keandalan perangkat lunak.',
            'description' => 'Merancang pengujian aplikasi dan melaporkan temuan kepada tim pengembangan.',
            'illustration' => UploadedFile::fake()->image('qa.png', 600, 400),
            'entry_level' => 'Entry Level', 'career_start' => 'Junior', 'career_end' => 'Senior',
            'industries_text' => 'Teknologi, Keuangan',
            'prospects' => ['Mengembangkan keahlian otomasi pengujian.'],
            'responsibilities' => ['Menyusun test plan.', 'Melakukan pengujian regresi.'],
            'competency_ids' => Competency::orderBy('id')->limit(2)->pluck('id')->all(),
            'tools' => [['name' => 'Postman', 'usage' => 'Menguji REST API.', 'logo' => UploadedFile::fake()->image('tool.png')]],
        ], $overrides);
    }

    public function test_admin_can_open_list_editor_detail_and_sidebar_link(): void
    {
        $profession = Profession::firstOrFail();
        $this->actingAs($this->user('admin'), 'web')->get('/home')->assertSee('href="'.route('admin.professions.index').'"', false);
        $this->get('/admin/professions')->assertOk()->assertSee('Tambah Profesi');
        $this->get('/admin/professions/create')->assertOk()->assertSee('Informasi dasar')->assertSee('Profil dan prospek karier')
            ->assertSee('Tanggung jawab dan kompetensi')->assertSee('Tools / teknologi')->assertDontSee('Mata kuliah relevan');
        $this->get('/admin/professions/'.$profession->id)->assertOk()->assertSee($profession->name);
        $this->get('/admin/professions/'.$profession->id.'/edit')->assertOk()->assertSee('Edit Profesi');
    }

    public function test_only_admin_can_use_profession_web_routes_and_media(): void
    {
        $profession = Profession::firstOrFail();
        $base = '/admin/professions';
        $this->get($base)->assertRedirect(route('login'));
        foreach (['mahasiswa', 'assessor'] as $role) {
            $this->actingAs($this->user($role), 'web');
            foreach ([$base, $base.'/create', $base.'/'.$profession->id, $base.'/'.$profession->id.'/edit', $base.'/'.$profession->id.'/illustration'] as $url) {
                $this->get($url)->assertForbidden();
            }
            $this->post($base, ['action' => 'draft', 'name' => 'Forbidden'])->assertForbidden();
            $this->put($base.'/'.$profession->id, ['action' => 'draft', 'name' => 'Forbidden'])->assertForbidden();
            $this->delete($base.'/'.$profession->id)->assertForbidden();
        }
        $this->assertDatabaseCount('professions', 5);
    }

    public function test_name_only_draft_is_saved_and_never_exposed_to_student_api(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', ['action' => 'draft', 'name' => 'Draft Profession'])->assertRedirect();
        $profession = Profession::where('name', 'Draft Profession')->firstOrFail();
        $this->assertFalse($profession->is_active);
        $this->assertSame('draft', $profession->publication_status);
        $this->assertNull($profession->career_category_id);
        $this->get('/admin/professions/'.$profession->id.'/edit')->assertOk()->assertSee('Draft Profession');
        $this->authenticate('admin')->getJson('/api/admin/professions/'.$profession->id)->assertOk()->assertJsonPath('data.career_category', null);
        $this->authenticate()->getJson('/api/professions?keyword=Draft')->assertOk()->assertJsonPath('data', []);
        $this->getJson('/api/professions/'.$profession->id)->assertNotFound();
        // Even an accidental active flag cannot expose an unpublished draft.
        $profession->update(['is_active' => true]);
        $this->getJson('/api/professions/'.$profession->id)->assertNotFound();
    }

    public function test_publish_requires_complete_fields_and_illustration(): void
    {
        $this->actingAs($this->user('admin'), 'web')->postJson('/admin/professions', ['action' => 'publish', 'name' => 'Incomplete'])
            ->assertUnprocessable()->assertJsonValidationErrors(['summary', 'career_category_id', 'description', 'illustration', 'entry_level', 'career_start', 'career_end', 'industries', 'prospects', 'responsibilities', 'competency_ids']);
        $this->assertDatabaseCount('professions', 5);
    }

    public function test_publish_saves_all_low_fidelity_fields_relationships_and_media(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', $this->payload())->assertSessionHasNoErrors()->assertRedirect();
        $profession = Profession::where('name', 'Quality Assurance Engineer')->firstOrFail();
        $this->assertTrue($profession->is_active);
        $this->assertSame('published', $profession->publication_status);
        $this->assertSame(['Teknologi', 'Keuangan'], $profession->industries);
        $this->assertSame('Entry Level', $profession->entry_level);
        $this->assertSame(2, $profession->competencies()->count());
        $this->assertSame('Postman', $profession->tools[0]['name']);
        Storage::disk('local')->assertExists($profession->illustration_path);
        Storage::disk('local')->assertExists($profession->tools[0]['logo_path']);
        $this->get('/admin/professions/'.$profession->id.'/illustration')->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/admin/professions/'.$profession->id.'/tools/'.$profession->tools[0]['key'].'/logo')->assertOk();
        $this->get('/admin/professions/'.$profession->id)->assertOk()->assertSee('Menguji REST API.');
    }

    public function test_edit_preserves_images_and_synchronizes_competencies(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', $this->payload());
        $profession = Profession::where('name', 'Quality Assurance Engineer')->firstOrFail();
        $illustration = $profession->illustration_path;
        $logo = $profession->tools[0]['logo_path'];
        $data = $this->payload(['illustration' => null, 'competency_ids' => [Competency::firstOrFail()->id], 'tools' => [['key' => $profession->tools[0]['key'], 'name' => 'Postman Updated', 'usage' => 'Integrasi API.']]]);
        $this->put('/admin/professions/'.$profession->id, $data)->assertSessionHasNoErrors()->assertRedirect();
        $profession->refresh();
        $this->assertSame($illustration, $profession->illustration_path);
        $this->assertSame($logo, $profession->tools[0]['logo_path']);
        $this->assertSame('Postman Updated', $profession->tools[0]['name']);
        $this->assertSame(1, $profession->competencies()->count());
    }

    public function test_replaced_illustration_and_removed_tool_images_are_cleaned_up(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', $this->payload());
        $profession = Profession::where('name', 'Quality Assurance Engineer')->firstOrFail();
        $oldImage = $profession->illustration_path;
        $oldLogo = $profession->tools[0]['logo_path'];
        $this->put('/admin/professions/'.$profession->id, $this->payload(['tools' => []]))->assertSessionHasNoErrors();
        Storage::disk('local')->assertMissing($oldImage);
        Storage::disk('local')->assertMissing($oldLogo);
        Storage::disk('local')->assertExists($profession->fresh()->illustration_path);
    }

    public function test_validation_rejects_duplicate_competencies_invalid_ranges_and_uploads(): void
    {
        $id = Competency::firstOrFail()->id;
        $this->actingAs($this->user('admin'), 'web')->postJson('/admin/professions', $this->payload([
            'name' => 'Data Analyst', 'summary' => str_repeat('x', 201), 'competency_ids' => [$id, $id],
            'career_start' => 'Senior', 'career_end' => 'Junior',
            'illustration' => UploadedFile::fake()->create('not-image.svg', 10, 'image/svg+xml'),
        ]))->assertUnprocessable()->assertJsonValidationErrors(['name', 'summary', 'competency_ids.0', 'career_end', 'illustration']);
        $this->postJson('/admin/professions', $this->payload(['illustration' => UploadedFile::fake()->image('large.jpg')->size(2049)]))
            ->assertUnprocessable()->assertJsonValidationErrors('illustration');
        $this->assertSame([], Storage::disk('local')->allFiles('professions'));
    }

    public function test_deactivation_preserves_profession_pivot_and_media(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', $this->payload());
        $profession = Profession::where('name', 'Quality Assurance Engineer')->firstOrFail();
        $this->delete('/admin/professions/'.$profession->id)->assertRedirect(route('admin.professions.index'));
        $this->assertFalse($profession->fresh()->is_active);
        $this->assertSame(2, $profession->competencies()->count());
        Storage::disk('local')->assertExists($profession->illustration_path);
        $this->get('/admin/professions?status=inactive')->assertSee($profession->name);
    }

    public function test_search_empty_state_and_status_filters_are_real_database_results(): void
    {
        $this->actingAs($this->user('admin'), 'web')->post('/admin/professions', ['action' => 'draft', 'name' => 'Draft Profession']);
        $this->get('/admin/professions?status=draft')->assertOk()->assertSee('Draft Profession')->assertDontSee('Software Engineer');
        $this->get('/admin/professions?keyword=Data')->assertSee('Data Analyst')->assertDontSee('Software Engineer');
        $this->get('/admin/professions?keyword=no-matching-profession')->assertSee('Tidak ada profesi yang ditemukan');
        $this->getJson('/admin/professions?status=invalid')->assertUnprocessable();
    }
}
