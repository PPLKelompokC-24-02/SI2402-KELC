<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerActionPlanRequest;
use App\Models\ActionPlan;
use App\Models\ActionPlanTemplate;
use App\Support\AppLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CareerActionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user('web');

        // Ringkasan progres dari seluruh aktivitas milik mahasiswa
        $allPlans = $user->actionPlans()->get();
        $totalCount = $allPlans->count();
        $notStartedCount = $allPlans->where('status', ActionPlan::STATUS_NOT_STARTED)->count();
        $inProgressCount = $allPlans->where('status', ActionPlan::STATUS_IN_PROGRESS)->count();
        $completedCount = $allPlans->where('status', ActionPlan::STATUS_COMPLETED)->count();
        $completionPercentage = $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0;

        $summary = [
            'total' => $totalCount,
            'not_started' => $notStartedCount,
            'in_progress' => $inProgressCount,
            'completed' => $completedCount,
            'percentage' => $completionPercentage,
        ];

        // Filter status & pencarian judul/deskripsi
        $statusFilter = $request->query('status');
        $searchQuery = trim((string) $request->query('q', ''));

        $plansQuery = $user->actionPlans()->with('template')->latest();

        if ($statusFilter && in_array($statusFilter, ActionPlan::STATUSES, true)) {
            $plansQuery->where('status', $statusFilter);
        }

        if ($searchQuery !== '') {
            $plansQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $actionPlans = $plansQuery->get();
        $templates = ActionPlanTemplate::active()->orderBy('title')->get();

        return view('action-plan.index', [
            ...AppLayout::data($user),
            'templates' => $templates,
            'actionPlans' => $actionPlans,
            'summary' => $summary,
            'currentStatus' => $statusFilter ?: 'all',
            'searchQuery' => $searchQuery,
            'statuses' => ActionPlan::STATUSES,
        ]);
    }

    public function store(CareerActionPlanRequest $request): RedirectResponse
    {
        $user = $request->user('web');

        $user->actionPlans()->create($request->validated());

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil ditambahkan ke Career Action Plan Anda.');
    }

    public function storeFromTemplate(Request $request, ActionPlanTemplate $actionTemplate): RedirectResponse
    {
        abort_unless($actionTemplate->is_active, 404, 'Template tidak aktif.');

        $user = $request->user('web');
        $dueDate = $request->input('due_date');

        $user->actionPlans()->create([
            'template_id' => $actionTemplate->id,
            'title' => $actionTemplate->title,
            'description' => $actionTemplate->description,
            'due_date' => (is_string($dueDate) && trim($dueDate) !== '') ? trim($dueDate) : null,
            'status' => ActionPlan::STATUS_NOT_STARTED,
        ]);

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil ditambahkan ke Career Action Plan Anda dari template.');
    }

    public function edit(Request $request, ActionPlan $actionPlan): View
    {
        Gate::authorize('update', $actionPlan);

        $user = $request->user('web');

        return view('action-plan.edit', [
            ...AppLayout::data($user),
            'actionPlan' => $actionPlan,
            'statuses' => ActionPlan::STATUSES,
        ]);
    }

    public function update(CareerActionPlanRequest $request, ActionPlan $actionPlan): RedirectResponse
    {
        Gate::authorize('update', $actionPlan);

        $actionPlan->update($request->validated());

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil diperbarui.');
    }

    public function updateStatus(Request $request, ActionPlan $actionPlan): RedirectResponse
    {
        Gate::authorize('update', $actionPlan);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(ActionPlan::STATUSES)],
        ], [
            'status.required' => 'Status aktivitas wajib dipilih.',
            'status.in' => 'Status aktivitas tidak valid.',
        ]);

        $actionPlan->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('status', 'Status aktivitas berhasil diperbarui menjadi "'.$actionPlan->status.'".');
    }

    public function destroy(ActionPlan $actionPlan): RedirectResponse
    {
        Gate::authorize('delete', $actionPlan);

        $actionPlan->delete();

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil dihapus dari Career Action Plan.');
    }
}
