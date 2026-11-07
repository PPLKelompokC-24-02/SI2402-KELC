<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogRequest;
use App\Http\Resources\ActionPlanTemplateResource;
use App\Models\ActionPlanTemplate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ActionPlanTemplateController extends Controller
{
    public function index(CatalogRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ActionPlanTemplate::class);

        $templates = ActionPlanTemplate::active()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = '%'.$request->validated('keyword').'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->whereLike('title', $keyword)
                        ->orWhereLike('description', $keyword);
                });
            })
            ->orderBy('title')
            ->orderBy('id')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ActionPlanTemplateResource::collection($templates)->additional([
            'message' => $templates->total() ? 'Daftar template aktivitas aktif.' : 'Tidak ada template yang ditemukan.',
        ]);
    }

    public function show(ActionPlanTemplate $actionTemplate): ActionPlanTemplateResource
    {
        abort_unless($actionTemplate->is_active, 404, 'Template tidak aktif atau tidak ditemukan.');
        Gate::authorize('view', $actionTemplate);

        return (new ActionPlanTemplateResource($actionTemplate))->additional([
            'message' => 'Detail template aktivitas.',
        ]);
    }
}
