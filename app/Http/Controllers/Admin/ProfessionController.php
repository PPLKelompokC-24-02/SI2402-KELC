<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogRequest;
use App\Http\Requests\ProfessionRequest;
use App\Http\Resources\ProfessionResource;
use App\Models\Profession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProfessionController extends Controller
{
    public function index(CatalogRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Profession::class);
        $professions = Profession::with(['careerCategory', 'competencies'])
            ->when($request->filled('keyword'), fn ($query) => $query->whereLike('name', '%'.$request->validated('keyword').'%'))
            ->orderBy('name')->orderBy('id')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        return ProfessionResource::collection($professions)->additional(['message' => $professions->total() ? 'Daftar profesi.' : 'Tidak ada profesi yang ditemukan.']);
    }

    public function store(ProfessionRequest $request): JsonResponse
    {
        Gate::authorize('create', Profession::class);
        $profession = DB::transaction(function () use ($request) {
            $profession = Profession::create($request->safe()->except('competency_ids'));
            $profession->competencies()->sync($request->validated('competency_ids'));

            return $profession;
        });

        return response()->json(['message' => 'Profesi berhasil ditambahkan.', 'data' => new ProfessionResource($profession->load(['careerCategory', 'competencies']))], 201);
    }

    public function show(Profession $profession): ProfessionResource
    {
        Gate::authorize('view', $profession);

        return (new ProfessionResource($profession->load(['careerCategory', 'competencies'])))->additional(['message' => 'Detail profesi.']);
    }

    public function update(ProfessionRequest $request, Profession $profession): ProfessionResource
    {
        Gate::authorize('update', $profession);
        DB::transaction(function () use ($request, $profession) {
            $profession->update($request->safe()->except('competency_ids'));
            if ($request->has('competency_ids')) {
                $profession->competencies()->sync($request->validated('competency_ids'));
            }
        });

        return (new ProfessionResource($profession->load(['careerCategory', 'competencies'])))->additional(['message' => 'Profesi berhasil diperbarui.']);
    }

    public function destroy(Profession $profession): JsonResponse
    {
        Gate::authorize('delete', $profession);
        $profession->update(['is_active' => false]);

        return response()->json(['message' => 'Profesi berhasil dinonaktifkan. Data dan relasi tetap tersimpan.']);
    }
}
