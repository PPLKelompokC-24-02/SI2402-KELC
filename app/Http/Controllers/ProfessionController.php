<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogRequest;
use App\Http\Resources\ProfessionResource;
use App\Models\Profession;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProfessionController extends Controller
{
    public function index(CatalogRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Profession::class);
        $professions = Profession::with(['careerCategory', 'competencies'])->where('is_active', true)
            ->when($request->filled('keyword'), fn ($query) => $query->whereLike('name', '%'.$request->validated('keyword').'%'))
            ->orderBy('name')->orderBy('id')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        return ProfessionResource::collection($professions)->additional(['message' => $professions->total() ? 'Katalog profesi aktif.' : 'Tidak ada profesi yang ditemukan.']);
    }

    public function show(int $profession): ProfessionResource
    {
        $profession = Profession::with(['careerCategory', 'competencies'])->where('is_active', true)->findOrFail($profession);
        Gate::authorize('view', $profession);

        return (new ProfessionResource($profession))->additional(['message' => 'Detail profesi.']);
    }
}
