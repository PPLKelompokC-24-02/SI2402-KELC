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

        // A neutral selection of published professions, independent of assessment.
        return $this->page($request, 'careers.index', [
            'categories' => CareerCategory::orderBy('name')->get(),
            'professions' => $this->visible()->with('careerCategory')->orderBy('name')->limit(3)->get(),
        ]);
    }

    public function professions(ExploreProfessionsRequest $request): View
    {
        Gate::authorize('viewAny', Profession::class);
        $filters = $request->safe()->only(['keyword', 'category', 'page', 'per_page']);
        $professions = $this->visible()->with('careerCategory')
            ->when($request->filled('keyword'), fn (Builder $query) => $query->whereLike('name', '%'.$request->validated('keyword').'%'))
            ->when($request->filled('category'), fn (Builder $query) => $query->where('career_category_id', $request->validated('category')))
            ->orderBy('name')->orderBy('id')->paginate($request->integer('per_page', 10))->withQueryString();

        return $this->page($request, 'careers.professions', [
            'professions' => $professions,
            'categories' => CareerCategory::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(ExploreProfessionsRequest $request, int $profession): View
    {
        $profession = $this->visible()->with(['careerCategory', 'competencies'])->findOrFail($profession);
        Gate::authorize('view', $profession);

        return $this->page($request, 'careers.show', [
            'profession' => $profession,
            'tab' => $request->validated('tab') ?? 'overview',
            'filters' => $request->safe()->only(['keyword', 'category', 'page', 'per_page']),
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
