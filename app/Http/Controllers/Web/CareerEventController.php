<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CareerEvent;
use App\Support\AppLayout;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CareerEventController extends Controller
{
    /**
     * AC-01, AC-02, AC-03, AC-07: Menampilkan daftar Career Event published
     * dengan fitur pencarian kata kunci dan filter kategori.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', CareerEvent::class);

        $events = CareerEvent::query()
            ->status(CareerEvent::STATUS_PUBLISHED)
            ->search($request->query('keyword'))
            ->category($request->query('category'))
            ->orderByDesc('start_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        $categories = CareerEvent::CATEGORIES;

        return $this->page($request, 'events.index', [
            'events'          => $events,
            'categories'      => $categories,
            'currentCategory' => $request->query('category'),
            'currentKeyword'  => $request->query('keyword'),
        ]);
    }

    /**
     * AC-04, AC-05, AC-06: Menampilkan detail satu Career Event.
     * Policy memastikan mahasiswa hanya bisa melihat event published.
     */
    public function show(Request $request, CareerEvent $event): View
    {
        Gate::authorize('view', $event);

        return $this->page($request, 'events.show', [
            'event' => $event,
        ]);
    }

    /**
     * AC-05: Menyajikan file gambar poster event dari private storage secara aman.
     * Mahasiswa hanya dapat mengakses poster event yang berstatus published.
     */
    public function poster(CareerEvent $event): StreamedResponse
    {
        Gate::authorize('view', $event);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        abort_unless($event->poster_path && $disk->exists($event->poster_path), 404);

        return $disk->response($event->poster_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'public, max-age=3600',
        ]);
    }

    /**
     * Helper untuk render view dengan data layout Career Compass.
     */
    private function page(Request $request, string $view, array $data): View
    {
        return view($view, [
            ...AppLayout::data($request->user('web')),
            ...$data,
        ]);
    }
}

