<?php

namespace Tests\Feature;

use App\Models\ActionPlanTemplate;
use PHPUnit\Framework\Attributes\DataProvider;

class ActionPlanTemplateApiTest extends ApiTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'title' => 'Mengikuti Sertifikasi Cloud Computing',
            'description' => 'Mempersiapkan diri dan mengikuti sertifikasi Cloud Computing tingkat pemula hingga menengah.',
            'is_active' => true,
        ], $overrides);
    }

    public static function nonAdmins(): array
    {
        return [['mahasiswa'], ['assessor']];
    }

    public function test_admin_can_create_view_and_update_template_via_api(): void
    {
        $created = $this->authenticate('admin')->postJson('/api/admin/action-templates', $this->payload());
        $created->assertCreated()
            ->assertJsonPath('data.title', 'Mengikuti Sertifikasi Cloud Computing')
            ->assertJsonPath('data.is_active', true);

        $id = $created->json('data.id');

        $this->getJson('/api/admin/action-templates/'.$id)
            ->assertOk()
            ->assertJsonPath('data.title', 'Mengikuti Sertifikasi Cloud Computing');

        $this->putJson('/api/admin/action-templates/'.$id, $this->payload(['title' => 'Sertifikasi Cloud Lanjutan']))
            ->assertOk()
            ->assertJsonPath('data.title', 'Sertifikasi Cloud Lanjutan');

        $this->assertDatabaseHas('action_plan_templates', [
            'id' => $id,
            'title' => 'Sertifikasi Cloud Lanjutan',
        ]);
    }

    public function test_admin_can_list_and_filter_templates_via_api(): void
    {
        $template = ActionPlanTemplate::firstOrFail();
        $template->update(['is_active' => false]);

        $this->authenticate('admin')->getJson('/api/admin/action-templates?status=inactive')
            ->assertOk()
            ->assertJsonPath('data.0.id', $template->id);

        $this->getJson('/api/admin/action-templates?keyword='.$template->title)
            ->assertOk()
            ->assertJsonPath('data.0.title', $template->title);
    }

    public function test_admin_can_deactivate_template_via_api(): void
    {
        $template = ActionPlanTemplate::firstOrFail();

        $this->authenticate('admin')->deleteJson('/api/admin/action-templates/'.$template->id)
            ->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->assertFalse($template->fresh()->is_active);
    }

    public function test_student_can_list_active_templates_and_not_inactive(): void
    {
        $activeCount = ActionPlanTemplate::where('is_active', true)->count();
        $inactive = ActionPlanTemplate::create([
            'title' => 'Template Khusus Tertutup',
            'description' => 'Tidak untuk konsumsi mahasiswa.',
            'is_active' => false,
        ]);

        $response = $this->authenticate('mahasiswa')->getJson('/api/action-templates');
        $response->assertOk()
            ->assertJsonPath('meta.total', $activeCount);

        // Student cannot show inactive template
        $this->getJson('/api/action-templates/'.$inactive->id)->assertNotFound();
    }

    #[DataProvider('nonAdmins')]
    public function test_non_admins_cannot_access_admin_api_endpoints(string $role): void
    {
        $template = ActionPlanTemplate::firstOrFail();
        $base = '/api/admin/action-templates';

        $this->authenticate($role);
        $this->getJson($base)->assertForbidden();
        $this->postJson($base, $this->payload())->assertForbidden();
        $this->getJson($base.'/'.$template->id)->assertForbidden();
        $this->putJson($base.'/'.$template->id, $this->payload())->assertForbidden();
        $this->deleteJson($base.'/'.$template->id)->assertForbidden();
    }
}

