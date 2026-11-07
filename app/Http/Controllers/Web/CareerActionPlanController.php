<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionPlan;
use App\Models\ActionPlanTemplate;
use App\Support\AppLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CareerActionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user('web');
        $templates = ActionPlanTemplate::active()->orderBy('title')->get();
        $actionPlans = $user->actionPlans()->with('template')->latest()->get();

        return view('action-plan.index', [
            ...AppLayout::data($user),
            'templates' => $templates,
            'actionPlans' => $actionPlans,
        ]);
    }

    public function storeFromTemplate(Request $request, ActionPlanTemplate $actionTemplate): RedirectResponse
    {
        abort_unless($actionTemplate->is_active, 404, 'Template tidak aktif.');

        $user = $request->user('web');

        $user->actionPlans()->create([
            'template_id' => $actionTemplate->id,
            'title' => $actionTemplate->title,
            'description' => $actionTemplate->description,
            'status' => 'Not Started',
        ]);

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil ditambahkan ke Career Action Plan Anda dari template.');
    }

    public function destroy(ActionPlan $actionPlan): RedirectResponse
    {
        Gate::authorize('delete', $actionPlan);

        $actionPlan->delete();

        return redirect()->route('action-plan.index')
            ->with('status', 'Aktivitas berhasil dihapus dari Career Action Plan.');
    }
}
