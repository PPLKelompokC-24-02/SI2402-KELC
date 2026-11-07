<?php

namespace Tests\Feature;

use App\Models\CareerCategory;
use App\Models\Profession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CareerExplorationWebTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_student_menu_opens_exploration_without_an_assessment(): void
    {
        $this->actingAs($this->user(), 'web')->get('/home')->assertSee('href="'.route('careers.index').'"', false);
        $this->get('/career-exploration')->assertOk()->assertViewIs('careers.index')
            ->assertSee('Jelajahi bidang karier')->assertSee('Pilihan profesi untuk dijelajahi')
            ->assertSee('Business Analyst')->assertSee('Data Analyst')->assertSee('aria-current="page"', false)
            ->assertDontSee('Rekomendasi otomatis');
    }

    public function test_search_and_category_filter_use_published_database_records(): void
    {
        $category = CareerCategory::where('name', 'Data & Analytics')->firstOrFail();
        $this->actingAs($this->user(), 'web')->get('/professions?keyword=ANALYST&category='.$category->id)
            ->assertOk()->assertSee('Data Analyst')->assertDontSee('Business Analyst')->assertDontSee('Software Engineer');
        $this->get('/professions?keyword='.urlencode("' OR 1=1 --"))->assertOk()->assertSee('Tidak ada profesi yang ditemukan');
        $this->get('/professions?keyword=tidak-tersedia')->assertOk()->assertSee('Tidak ada profesi yang ditemukan')->assertSee('Reset pencarian');
    }

    public function test_catalog_pagination_keeps_filters_and_detail_return_link(): void
    {
        $category = CareerCategory::firstOrFail();
        for ($index = 0; $index < 12; $index++) {
            Profession::create(['name' => 'Test Profession '.str_pad($index, 2, '0', STR_PAD_LEFT), 'description' => 'Contoh profesi.', 'responsibilities' => ['Contoh tanggung jawab.'], 'career_category_id' => $category->id]);
        }
        $response = $this->actingAs($this->user(), 'web')->get('/professions?keyword=Test&category='.$category->id.'&page=2');
        $response->assertOk()->assertSee('Test Profession 10')->assertDontSee('Test Profession 00');
        $paginator = $response->viewData('professions');
        $this->assertSame(12, $paginator->total());
        $this->assertSame(2, $paginator->currentPage());
        $this->assertStringContainsString('keyword=Test', $paginator->previousPageUrl());
        $profession = $paginator->first();
        $this->get('/professions/'.$profession->id.'?keyword=Test&category='.$category->id.'&page=2')
            ->assertSee('href="'.e(route('careers.professions.index', ['keyword' => 'Test', 'category' => (string) $category->id, 'page' => '2'])).'"', false);
    }

    public function test_details_display_metadata_responsibilities_competencies_and_tools(): void
    {
        $profession = Profession::where('name', 'Data Analyst')->firstOrFail();
        $profession->update([
            'summary' => 'Ringkasan profesi data.', 'entry_level' => 'Entry Level', 'career_start' => 'Junior', 'career_end' => 'Senior',
            'industries' => ['Teknologi', 'Keuangan'], 'prospects' => ['Mengembangkan keahlian analisis.'],
            'tools' => [['key' => (string) Str::uuid(), 'name' => 'Spreadsheet Tool', 'usage' => 'Menganalisis data.', 'logo_path' => null]],
        ]);
        $this->actingAs($this->user(), 'web')->get('/professions/'.$profession->id)->assertOk()
            ->assertSee('Ringkasan profesi data.')->assertSee('Junior – Senior')->assertSee('Mengembangkan keahlian analisis.')
            ->assertSee($profession->description);
        $this->get('/professions/'.$profession->id.'?tab=responsibilities')->assertOk()->assertSee($profession->responsibilities[0]);
        $this->get('/professions/'.$profession->id.'?tab=competencies')->assertOk()->assertSee('SQL')->assertSee('Statistics');
        $this->get('/professions/'.$profession->id.'?tab=tools')->assertOk()->assertSee('Spreadsheet Tool')->assertSee('Menganalisis data.');
        $this->get('/professions/'.$profession->id.'?tab=path')->assertOk()->assertSee('Junior')->assertSee('Senior')->assertSee('Rincian tahapan');
    }

    public function test_courses_and_missing_path_or_tools_show_honest_empty_states(): void
    {
        $profession = Profession::firstOrFail();
        $this->actingAs($this->user(), 'web')->get('/professions/'.$profession->id.'?tab=courses')->assertOk()->assertSee('Mata Kuliah yang Relevan')->assertSee('Data belum tersedia');
        $this->get('/professions/'.$profession->id.'?tab=path')->assertOk()->assertSee('Data jalur karier belum tersedia')->assertDontSee('0 – 2 tahun');
        $this->get('/professions/'.$profession->id.'?tab=tools')->assertOk()->assertSee('Data tools belum tersedia');
    }

    public function test_drafts_and_inactive_professions_are_hidden_on_all_student_routes(): void
    {
        $hidden = Profession::firstOrFail();
        $hidden->update(['is_active' => false]);
        $draft = Profession::create(['name' => 'Unpublished Profession', 'publication_status' => 'draft', 'is_active' => true]);
        $this->actingAs($this->user(), 'web')->get('/career-exploration')->assertDontSee($hidden->name)->assertDontSee($draft->name);
        $this->get('/professions')->assertDontSee($hidden->name)->assertDontSee($draft->name);
        foreach ([$hidden, $draft] as $profession) {
            $this->get('/professions/'.$profession->id)->assertNotFound();
            $this->get('/professions/'.$profession->id.'/illustration')->assertNotFound();
            $this->get('/professions/'.$profession->id.'/tools/'.Str::uuid().'/logo')->assertNotFound();
        }
    }

    public function test_media_is_authorized_and_unavailable_after_deactivation(): void
    {
        $profession = Profession::firstOrFail();
        $image = UploadedFile::fake()->image('profession.jpg')->store('professions', 'local');
        $logo = UploadedFile::fake()->image('logo.png')->store('professions', 'local');
        $key = (string) Str::uuid();
        $profession->update(['illustration_path' => $image, 'tools' => [['key' => $key, 'name' => 'Tool', 'usage' => 'Kegunaan', 'logo_path' => $logo]]]);
        $this->actingAs($this->user(), 'web')->get('/professions/'.$profession->id.'/illustration')
            ->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private');
        $this->get('/professions/'.$profession->id.'/tools/'.$key.'/logo')->assertOk();
        $this->get('/professions/'.$profession->id.'/tools/'.Str::uuid().'/logo')->assertNotFound();
        $profession->update(['is_active' => false]);
        $this->get('/professions/'.$profession->id.'/illustration')->assertNotFound();
        $this->get('/professions/'.$profession->id.'/tools/'.$key.'/logo')->assertNotFound();
    }

    public function test_non_students_cannot_access_exploration_details_or_media(): void
    {
        $profession = Profession::firstOrFail();
        $this->get('/career-exploration')->assertRedirect(route('login'));
        $this->get('/professions')->assertRedirect(route('login'));
        foreach (['admin', 'assessor'] as $role) {
            $this->actingAs($this->user($role), 'web');
            foreach (['/career-exploration', '/professions', '/professions/'.$profession->id, '/professions/'.$profession->id.'/illustration'] as $url) {
                $this->get($url)->assertForbidden();
            }
        }
    }

    public function test_queries_are_validated_and_output_is_escaped(): void
    {
        $profession = Profession::firstOrFail();
        $profession->update(['description' => '<script>alert("unsafe")</script>']);
        $this->actingAs($this->user(), 'web')->getJson('/professions?category=99999&keyword[]=x&page=0')
            ->assertUnprocessable()->assertJsonValidationErrors(['category', 'keyword', 'page']);
        $this->getJson('/professions/'.$profession->id.'?tab=invalid')->assertUnprocessable()->assertJsonValidationErrors('tab');
        $this->get('/professions/'.$profession->id)->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(', false);
        $this->get('/professions/99999')->assertNotFound();
    }
}
