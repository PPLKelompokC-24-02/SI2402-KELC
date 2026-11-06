<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCareerEventRequest;
use App\Models\CareerEvent;
use App\Support\AppLayout;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CareerEventController extends Controller
{
    /**
     * AC-02: Menampilkan daftar seluruh Career Event dengan pencarian dan filter kategori.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', CareerEvent::class);

        $events = CareerEvent::query()
            ->search($request->query('keyword'))
            ->category($request->query('category'))
            ->status($request->query('status'))
            ->orderByDesc('start_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        $categories = CareerEvent::CATEGORIES;

        return $this->page($request, 'admin.events.index', [
            'events' => $events,
            'categories' => $categories,
            'currentCategory' => $request->query('category'),
            'currentStatus' => $request->query('status'),
            'currentKeyword' => $request->query('keyword'),
        ]);
    }

    /**
     * AC-01: Menampilkan form penambahan Career Event baru.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', CareerEvent::class);

        return $this->editor($request, new CareerEvent([
            'status' => CareerEvent::STATUS_PUBLISHED,
        ]));
    }

    /**
     * AC-01: Menyimpan data Career Event baru ke database.
     */
    public function store(AdminCareerEventRequest $request): RedirectResponse
    {
        Gate::authorize('create', CareerEvent::class);

        $createdFiles = [];

        try {
            $event = DB::transaction(function () use ($request, &$createdFiles) {
                $data = $request->validated();
                $slug = CareerEvent::generateUniqueSlug($data['title']);

                $posterPath = null;
                if ($request->hasFile('poster')) {
                    $posterPath = $this->storePoster($request->file('poster'), $createdFiles);
                }

                return CareerEvent::create([
                    'title' => $data['title'],
                    'slug' => $slug,
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'organizer' => $data['organizer'],
                    'start_at' => $data['start_at'],
                    'end_at' => $data['end_at'] ?? null,
                    'location' => $data['location'],
                    'poster_path' => $posterPath,
                    'status' => $data['status'],
                    'registration_url' => $data['registration_url'],
                ]);
            });
        } catch (Throwable $e) {
            Storage::disk('local')->delete($createdFiles);
            throw $e;
        }

        $message = $event->isPublished()
            ? 'Career Event berhasil ditambahkan dan diterbitkan.'
            : 'Draf Career Event berhasil disimpan.';

        return redirect()->route('admin.events.index')->with('status', $message);
    }

    /**
     * AC-02: Menampilkan detail satu Career Event berdasarkan ID.
     */
    public function show(Request $request, CareerEvent $event): View
    {
        Gate::authorize('view', $event);

        return $this->page($request, 'admin.events.show', [
            'event' => $event,
        ]);
    }

    /**
     * AC-03: Menampilkan form untuk mengubah Career Event.
     */
    public function edit(Request $request, CareerEvent $event): View
    {
        Gate::authorize('update', $event);

        return $this->editor($request, $event);
    }

    /**
     * AC-03: Menyimpan perubahan Career Event ke database.
     */
    public function update(AdminCareerEventRequest $request, CareerEvent $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $createdFiles = [];
        $retiredFiles = [];

        try {
            DB::transaction(function () use ($request, $event, &$createdFiles, &$retiredFiles) {
                $data = $request->validated();

                // Perbarui slug jika judul berubah
                if ($event->title !== $data['title']) {
                    $event->slug = CareerEvent::generateUniqueSlug($data['title'], $event->id);
                }

                // Penanganan pergantian poster
                if ($request->boolean('remove_poster') || $request->hasFile('poster')) {
                    if ($event->poster_path) {
                        $retiredFiles[] = $event->poster_path;
                    }
                    $event->poster_path = null;
                }

                if ($request->hasFile('poster')) {
                    $event->poster_path = $this->storePoster($request->file('poster'), $createdFiles);
                }

                $event->fill([
                    'title' => $data['title'],
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'organizer' => $data['organizer'],
                    'start_at' => $data['start_at'],
                    'end_at' => $data['end_at'] ?? null,
                    'location' => $data['location'],
                    'status' => $data['status'],
                    'registration_url' => $data['registration_url'],
                ])->save();
            });
        } catch (Throwable $e) {
            Storage::disk('local')->delete($createdFiles);
            throw $e;
        }

        // Hapus file poster lama yang sudah diganti setelah transaksi berhasil
        Storage::disk('local')->delete($retiredFiles);

        $message = $event->isPublished()
            ? 'Perubahan Career Event berhasil disimpan dan diterbitkan.'
            : 'Perubahan draf Career Event berhasil disimpan.';

        // AC-06: Menampilkan informasi event yang telah diperbarui sesuai perubahan Admin
        return redirect()->route('admin.events.show', $event)->with('status', $message);
    }

    /**
     * AC-04: Menghapus data Career Event dari database.
     */
    public function destroy(CareerEvent $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        $posterPath = $event->poster_path;
        $eventTitle = $event->title;

        $event->delete();

        if ($posterPath) {
            Storage::disk('local')->delete($posterPath);
        }

        return redirect()->route('admin.events.index')->with(
            'status',
            'Career Event "'.$eventTitle.'" berhasil dihapus.'
        );
    }

    /**
     * Menyajikan file gambar poster event dari private storage secara aman.
     */
    public function poster(CareerEvent $event): StreamedResponse
    {
        Gate::authorize('view', $event);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        abort_unless($event->poster_path && $disk->exists($event->poster_path), 404);

        return $disk->response($event->poster_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-cache, private, must-revalidate',
        ]);
    }

    /**
     * Helper untuk upload poster ke storage/app/private/events.
     */
    private function storePoster($file, array &$createdFiles): string
    {
        $path = $file->store('events', 'local');
        if (! $path) {
            throw new RuntimeException('Poster belum dapat disimpan. Silakan coba lagi.');
        }
        $createdFiles[] = $path;

        return $path;
    }

    /**
     * Helper untuk render halaman form tambah/edit.
     */
    private function editor(Request $request, CareerEvent $event): View
    {
        return $this->page($request, 'admin.events.form', [
            'event' => $event,
            'categories' => CareerEvent::CATEGORIES,
        ]);
    }

    /**
     * Helper untuk render view dengan data layout Career Compass.
     */
    private function page(Request $request, string $view, array $data): View
    {
        $errors = session('errors') ?? (view()->shared('errors') ?? new ViewErrorBag);

        return view($view, [
            'errors' => $errors,
            ...AppLayout::data($request->user('web')),
            ...$data,
        ]);
    }
}
