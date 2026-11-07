<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminActionPlanTemplateIndexRequest;
use App\Http\Requests\AdminActionPlanTemplateRequest;
use App\Models\ActionPlanTemplate;
use App\Support\AppLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActionPlanTemplateController extends Controller
{
    public function index(AdminActionPlanTemplateIndexRequest $request): View
    {
        Gate::authorize('viewAny', ActionPlanTemplate::class);

        $templates = ActionPlanTemplate::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = '%'.$request->validated('keyword').'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->whereLike('title', $keyword)
                        ->orWhereLike('description', $keyword);
                });
            })
            ->when($request->input('status') === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return $this->page($request, 'admin.action-templates.index', compact('templates'));
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', ActionPlanTemplate::class);

        return $this->editor($request, new ActionPlanTemplate(['is_active' => true]));
    }

    public function store(AdminActionPlanTemplateRequest $request): RedirectResponse
    {
        Gate::authorize('create', ActionPlanTemplate::class);

        $template = ActionPlanTemplate::create([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.action-templates.index')
            ->with('status', 'Template aktivitas berhasil ditambahkan.');
    }

    public function show(Request $request, ActionPlanTemplate $actionTemplate): View
    {
        Gate::authorize('view', $actionTemplate);

        return $this->page($request, 'admin.action-templates.show', [
            'template' => $actionTemplate,
        ]);
    }

    public function edit(Request $request, ActionPlanTemplate $actionTemplate): View
    {
        Gate::authorize('update', $actionTemplate);

        return $this->editor($request, $actionTemplate);
    }

    public function update(AdminActionPlanTemplateRequest $request, ActionPlanTemplate $actionTemplate): RedirectResponse
    {
        Gate::authorize('update', $actionTemplate);

        $actionTemplate->update([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.action-templates.show', $actionTemplate)
            ->with('status', 'Template aktivitas berhasil diperbarui.');
    }

    public function destroy(Request $request, ActionPlanTemplate $actionTemplate): RedirectResponse
    {
        Gate::authorize('delete', $actionTemplate);

        if ($request->input('action') === 'deactivate') {
            $actionTemplate->update(['is_active' => false]);

            return redirect()->route('admin.action-templates.index')
                ->with('status', 'Template aktivitas dinonaktifkan. Aktivitas mahasiswa yang telah dibuat tetap tersimpan.');
        }

        $actionTemplate->delete();

        return redirect()->route('admin.action-templates.index')
            ->with('status', 'Template aktivitas berhasil dihapus.');
    }

    public function toggleStatus(ActionPlanTemplate $actionTemplate): RedirectResponse
    {
        Gate::authorize('update', $actionTemplate);

        $actionTemplate->update(['is_active' => ! $actionTemplate->is_active]);

        $statusText = $actionTemplate->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('status', "Template aktivitas berhasil {$statusText}.");
    }

    private function editor(Request $request, ActionPlanTemplate $template): View
    {
        return $this->page($request, 'admin.action-templates.form', compact('template'));
    }

    private function page(Request $request, string $view, array $data): View
    {
        return view($view, [...AppLayout::data($request->user('web')), ...$data]);
    }
}
