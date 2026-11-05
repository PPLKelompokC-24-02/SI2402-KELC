<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminActionPlanTemplateIndexRequest;
use App\Http\Requests\AdminActionPlanTemplateRequest;
use App\Http\Resources\ActionPlanTemplateResource;
use App\Models\ActionPlanTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ActionPlanTemplateController extends Controller
{
    public function index(AdminActionPlanTemplateIndexRequest $request): AnonymousResourceCollection
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
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ActionPlanTemplateResource::collection($templates)->additional([
            'message' => $templates->total() ? 'Daftar template aktivitas.' : 'Tidak ada template yang ditemukan.',
        ]);
    }

    public function store(AdminActionPlanTemplateRequest $request): JsonResponse
    {
        Gate::authorize('create', ActionPlanTemplate::class);

        $template = ActionPlanTemplate::create([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message' => 'Template aktivitas berhasil ditambahkan.',
            'data' => new ActionPlanTemplateResource($template),
        ], 201);
    }

    public function show(ActionPlanTemplate $actionTemplate): ActionPlanTemplateResource
    {
        Gate::authorize('view', $actionTemplate);

        return (new ActionPlanTemplateResource($actionTemplate))->additional([
            'message' => 'Detail template aktivitas.',
        ]);
    }

    public function update(AdminActionPlanTemplateRequest $request, ActionPlanTemplate $actionTemplate): ActionPlanTemplateResource
    {
        Gate::authorize('update', $actionTemplate);

        $actionTemplate->update([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', $actionTemplate->is_active),
        ]);

        return (new ActionPlanTemplateResource($actionTemplate))->additional([
            'message' => 'Template aktivitas berhasil diperbarui.',
        ]);
    }

    public function destroy(Request $request, ActionPlanTemplate $actionTemplate): JsonResponse
    {
        Gate::authorize('delete', $actionTemplate);

        if ($request->input('action') === 'delete' || $request->boolean('force')) {
            $actionTemplate->delete();

            return response()->json([
                'message' => 'Template aktivitas berhasil dihapus.',
            ]);
        }

        $actionTemplate->update(['is_active' => false]);

        return response()->json([
            'message' => 'Template aktivitas berhasil dinonaktifkan.',
            'data' => new ActionPlanTemplateResource($actionTemplate),
        ]);
    }
}

