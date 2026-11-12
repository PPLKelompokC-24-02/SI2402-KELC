<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExploreProfessionsRequest;
use App\Models\CareerCategory;
use App\Models\Profession;
use App\Support\AppLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CareerExplorationController extends Controller
{
    public function index(ExploreProfessionsRequest $request): View
    {
        Gate::authorize('viewAny', Profession::class);
        $user = $request->user('web');
        $savedProfessionIds = $user ? $user->savedProfessions()->pluck('professions.id')->all() : [];

        // A neutral selection of published professions, independent of assessment.
        return $this->page($request, 'careers.index', [
            'categories' => CareerCategory::orderBy('name')->get(),
            'professions' => $this->visible()->with('careerCategory')->orderBy('name')->limit(3)->get(),
            'savedProfessionIds' => $savedProfessionIds,
        ]);
    }

    public function professions(ExploreProfessionsRequest $request): View
    {
        Gate::authorize('viewAny', Profession::class);
        $user = $request->user('web');
        $tab = $request->input('tab') === 'favorites' ? 'favorites' : 'all';
        $filters = $request->safe()->only(['keyword', 'category', 'page', 'per_page', 'tab']);

        if ($tab === 'favorites') {
            $professionsQuery = $user ? $user->savedProfessions() : Profession::query()->whereRaw('1 = 0');
            $professions = $professionsQuery
                ->where('professions.is_active', true)
                ->where('professions.publication_status', 'published')
                ->with('careerCategory')
                ->when($request->filled('keyword'), fn (Builder $query) => $query->whereLike('professions.name', '%'.$request->validated('keyword').'%'))
                ->when($request->filled('category'), fn (Builder $query) => $query->where('professions.career_category_id', $request->validated('category')))
                ->orderBy('saved_professions.created_at', 'desc')
                ->paginate($request->integer('per_page', 10))
                ->withQueryString();
        } else {
            $professions = $this->visible()->with('careerCategory')
                ->when($request->filled('keyword'), fn (Builder $query) => $query->whereLike('name', '%'.$request->validated('keyword').'%'))
                ->when($request->filled('category'), fn (Builder $query) => $query->where('career_category_id', $request->validated('category')))
                ->orderBy('name')->orderBy('id')->paginate($request->integer('per_page', 10))->withQueryString();
        }

        $savedProfessionIds = $user ? $user->savedProfessions()->pluck('professions.id')->all() : [];
        $savedCount = $user ? $user->savedProfessions()->where('professions.is_active', true)->where('professions.publication_status', 'published')->count() : 0;

        return $this->page($request, 'careers.professions', [
            'professions' => $professions,
            'categories' => CareerCategory::orderBy('name')->get(),
            'filters' => $filters,
            'tab' => $tab,
            'savedProfessionIds' => $savedProfessionIds,
            'savedCount' => $savedCount,
        ]);
    }

    public function show(ExploreProfessionsRequest $request, int $profession): View
    {
        $profession = $this->visible()->with([
            'careerCategory',
            'competencies',
            'careerPathStages.competencies',
        ])->findOrFail($profession);
        Gate::authorize('view', $profession);
        $user = $request->user('web');

        return $this->page($request, 'careers.show', [
            'profession' => $profession,
            'tab' => $request->validated('tab') ?? 'overview',
            'filters' => $request->safe()->only(['keyword', 'category', 'page', 'per_page']),
            'isSaved' => $user ? $user->hasSavedProfession($profession) : false,
            'tabs' => [
                'overview' => 'Overview', 'responsibilities' => 'Tanggung Jawab',
                'competencies' => 'Kompetensi', 'path' => 'Jalur Karier',
                'courses' => 'Mata Kuliah', 'tools' => 'Tools & Teknologi',
            ],
        ]);
    }

    public function illustration(int $profession): StreamedResponse
    {
        $profession = $this->visible()->findOrFail($profession);
        Gate::authorize('view', $profession);

        return $this->image($profession->illustration_path);
    }

    public function toolLogo(int $profession, string $key): StreamedResponse
    {
        $profession = $this->visible()->findOrFail($profession);
        Gate::authorize('view', $profession);

        return $this->image(collect($profession->tools ?? [])->firstWhere('key', $key)['logo_path'] ?? null);
    }

    private function visible(): Builder
    {
        return Profession::query()->where('is_active', true)->where('publication_status', 'published');
    }

    private function image(?string $path): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        abort_unless($path && $disk->exists($path), 404);

        // Revalidate visibility on every request, including previously cached images.
        return $disk->response($path, null, ['X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, no-store']);
    }

    private function page(Request $request, string $view, array $data): View
    {
        return view($view, [...AppLayout::data($request->user('web')), ...$data]);
    }
}
