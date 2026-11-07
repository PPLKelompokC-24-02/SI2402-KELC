<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfessionIndexRequest;
use App\Http\Requests\WebProfessionRequest;
use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Profession;
use App\Services\SaveProfession;
use App\Support\AppLayout;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfessionController extends Controller
{
    public function index(AdminProfessionIndexRequest $request): View
    {
        Gate::authorize('viewAny', Profession::class);
        $professions = Profession::with('careerCategory')
            ->when($request->filled('keyword'), fn ($query) => $query->whereLike('name', '%'.$request->validated('keyword').'%'))
            ->when($request->input('status') === 'draft', fn ($query) => $query->where('publication_status', 'draft'))
            ->when($request->input('status') === 'published', fn ($query) => $query->where('publication_status', 'published')->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($query) => $query->where('publication_status', 'published')->where('is_active', false))
            ->orderByDesc('updated_at')->orderByDesc('id')->paginate(10)->withQueryString();

        return $this->page($request, 'admin.professions.index', compact('professions'));
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Profession::class);

        return $this->editor($request, new Profession(['is_active' => false, 'publication_status' => 'draft']));
    }

    public function store(WebProfessionRequest $request, SaveProfession $save): RedirectResponse
    {
        $profession = $save->handle($request);

        return redirect()->route('admin.professions.edit', $profession)->with('status', $profession->is_active ? 'Profesi berhasil diterbitkan.' : 'Draf profesi berhasil disimpan.');
    }

    public function show(Request $request, Profession $profession): View
    {
        Gate::authorize('view', $profession);

        return $this->page($request, 'admin.professions.show', ['profession' => $profession->load(['careerCategory', 'competencies'])]);
    }

    public function edit(Request $request, Profession $profession): View
    {
        Gate::authorize('update', $profession);

        return $this->editor($request, $profession);
    }

    public function update(WebProfessionRequest $request, Profession $profession, SaveProfession $save): RedirectResponse
    {
        $profession = $save->handle($request, $profession);

        return redirect()->route('admin.professions.edit', $profession)->with('status', $profession->is_active ? 'Perubahan profesi berhasil diterbitkan.' : 'Draf profesi berhasil disimpan.');
    }

    public function destroy(Profession $profession): RedirectResponse
    {
        Gate::authorize('delete', $profession);
        $profession->update(['is_active' => false]);

        return redirect()->route('admin.professions.index')->with('status', 'Profesi dinonaktifkan. Data dan relasinya tetap tersimpan.');
    }

    public function illustration(Profession $profession): StreamedResponse
    {
        Gate::authorize('view', $profession);

        return $this->image($profession->illustration_path);
    }

    public function toolLogo(Profession $profession, string $key): StreamedResponse
    {
        Gate::authorize('view', $profession);

        return $this->image(collect($profession->tools)->firstWhere('key', $key)['logo_path'] ?? null);
    }

    private function image(?string $path): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        abort_unless($path && $disk->exists($path), 404);

        return $disk->response($path, null, ['X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, max-age=300']);
    }

    private function editor(Request $request, Profession $profession): View
    {
        return $this->page($request, 'admin.professions.form', [
            'profession' => $profession->load('competencies'),
            'categories' => CareerCategory::orderBy('name')->get(),
            'competencies' => Competency::orderBy('name')->get(),
        ]);
    }

    private function page(Request $request, string $view, array $data): View
    {
        return view($view, [...AppLayout::data($request->user('web')), ...$data]);
    }
}
