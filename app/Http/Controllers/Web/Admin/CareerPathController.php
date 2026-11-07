<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerPathStageRequest;
use App\Http\Requests\ReorderCareerPathStagesRequest;
use App\Models\CareerPathStage;
use App\Models\Competency;
use App\Models\Profession;
use App\Support\AppLayout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CareerPathController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $professions = Profession::withCount('careerPathStages')
            ->with(['careerCategory', 'careerPathStages' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Daftar jalur karier profesi.',
            'data' => $professions,
        ]);
    }

    public function stages(Request $request, Profession $profession): View|JsonResponse
    {
        $stages = $profession->careerPathStages()
            ->with('competencies')
            ->orderBy('sort_order')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Daftar tahapan karier untuk profesi ' . $profession->name . '.',
                'profession' => [
                    'id' => $profession->id,
                    'name' => $profession->name,
                ],
                'data' => $stages,
            ]);
        }

        return view('admin.career-paths.manage', [
            ...AppLayout::data($request->user('web')),
            'profession' => $profession,
            'stages' => $stages,
            'competencies' => Competency::orderBy('name')->get(),
        ]);
    }

    public function show(Profession $profession, CareerPathStage $stage): JsonResponse
    {
        abort_unless($stage->profession_id === $profession->id, 404);

        return response()->json([
            'message' => 'Detail tahapan karier.',
            'data' => $stage->load('competencies'),
        ]);
    }

    public function store(CareerPathStageRequest $request, Profession $profession): JsonResponse|RedirectResponse
    {
        $stage = DB::transaction(function () use ($request, $profession) {
            $sortOrder = $request->filled('sort_order')
                ? $request->integer('sort_order')
                : (($profession->careerPathStages()->max('sort_order') ?? 0) + 1);

            $stage = $profession->careerPathStages()->create([
                'title' => $request->validated('title'),
                'description' => $request->validated('description'),
                'sort_order' => $sortOrder,
            ]);

            if ($request->has('competency_ids')) {
                $stage->competencies()->sync($request->validated('competency_ids') ?? []);
            }

            return $stage;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tahapan karier berhasil ditambahkan.',
                'data' => $stage->load('competencies'),
            ], 201);
        }

        return redirect()->back()->with('status', 'Tahapan karier berhasil ditambahkan.');
    }

    public function update(CareerPathStageRequest $request, Profession $profession, CareerPathStage $stage): JsonResponse|RedirectResponse
    {
        abort_unless($stage->profession_id === $profession->id, 404);

        DB::transaction(function () use ($request, $stage) {
            $data = $request->safe()->only(['title', 'description', 'sort_order']);
            $stage->update($data);

            if ($request->has('competency_ids')) {
                $stage->competencies()->sync($request->validated('competency_ids') ?? []);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tahapan karier berhasil diperbarui.',
                'data' => $stage->load('competencies'),
            ]);
        }

        return redirect()->back()->with('status', 'Tahapan karier berhasil diperbarui.');
    }

    public function destroy(Request $request, Profession $profession, CareerPathStage $stage): JsonResponse|RedirectResponse
    {
        abort_unless($stage->profession_id === $profession->id, 404);

        $stage->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tahapan karier berhasil dihapus. Master profesi dan kompetensi tetap aman.',
            ]);
        }

        return redirect()->back()->with('status', 'Tahapan karier berhasil dihapus.');
    }

    public function reorder(ReorderCareerPathStagesRequest $request, Profession $profession): JsonResponse|RedirectResponse
    {
        $stageIds = $request->validated('stage_ids');
        $existingCount = $profession->careerPathStages()->whereIn('id', $stageIds)->count();

        if ($existingCount !== count($stageIds)) {
            return response()->json([
                'message' => 'Satu atau lebih tahapan tidak valid untuk profesi ini.',
            ], 422);
        }

        DB::transaction(function () use ($profession, $stageIds) {
            foreach ($stageIds as $index => $id) {
                $profession->careerPathStages()->where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Urutan tahapan karier berhasil diperbarui.',
                'data' => $profession->careerPathStages()->with('competencies')->orderBy('sort_order')->get(),
            ]);
        }

        return redirect()->back()->with('status', 'Urutan tahapan karier berhasil diperbarui.');
    }
}