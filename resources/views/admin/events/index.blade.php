@extends('layouts.app')
@section('title', 'Manajemen Career Event — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
<link rel="stylesheet" href="{{ asset('css/careers.css') }}?v={{ filemtime(public_path('css/careers.css')) }}">
<style>
    .event-meta-sub { color: var(--muted); font-size: 11px; margin-top: 4px; }
    .event-badge-category { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 500; background: #eaf1f7; color: var(--navy); }
    .event-status--published { color: #32664f; background: #eef6ef; border: 1px solid #d9e8dc; }
    .event-status--draft { color: #776337; background: #faf7ed; border: 1px solid #eee7d1; }
    .event-status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 8px; border-radius: 12px; font-size: 10px; font-weight: 500; }
    .event-status-pill > span { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
    .filter-select-wrapper { min-width: 145px; }
    .filter-select-wrapper select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: white;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23566a5c' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px 14px;
        padding-right: 36px;
        padding-left: 14px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="profession-page">
    <p class="profession-eyebrow">MANAJEMEN DATA</p>
    <div class="profession-heading">
        <div>
            <h1>Manajemen Career Event</h1>
            <p>Kelola informasi kegiatan pengembangan kompetensi dan karier mahasiswa Telkom University.</p>
        </div>
        <a class="button button--primary" href="{{ route('admin.events.create') }}">
            <x-app.icon name="plus" /> Tambah Event
        </a>
    </div>

    @if (session('status'))
        <p class="profession-notice profession-notice--success" role="status">{{ session('status') }}</p>
    @endif

    {{-- Filter Kategori Interaktif (terinspirasi dari tampilan Career Explore) --}}
    <nav class="category-chips" aria-label="Filter kategori event">
        <a class="category-chip {{ ! request()->filled('category') ? 'is-selected' : '' }}" 
           href="{{ route('admin.events.index', array_filter(['keyword' => request('keyword'), 'status' => request('status')])) }}">
            Semua Kategori
        </a>
        @foreach ($categories as $cat)
            <a class="category-chip {{ request('category') === $cat ? 'is-selected' : '' }}" 
               href="{{ route('admin.events.index', array_filter(['category' => $cat, 'keyword' => request('keyword'), 'status' => request('status')])) }}">
                {{ $cat }}
            </a>
        @endforeach
    </nav>

    <section class="profession-list-card" aria-label="Daftar Career Event">
        <form class="profession-filters" method="GET" action="{{ route('admin.events.index') }}">
            @if (request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="search-field">
                <label class="sr-only" for="keyword">Cari judul, penyelenggara, atau lokasi event</label>
                <x-app.icon name="search" />
                <input class="form-control" id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul, penyelenggara, atau lokasi…" maxlength="150">
            </div>

            <div class="filter-select-wrapper">
                <label class="sr-only" for="status">Filter status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">Semua status</option>
                    <option value="published" @selected(request('status') === 'published')>Terbit</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draf</option>
                </select>
            </div>

            <button class="button button--outline" type="submit">Terapkan</button>

            @if (request()->filled('keyword') || request()->filled('category') || request()->filled('status'))
                <a class="text-link" href="{{ route('admin.events.index') }}">Reset</a>
            @endif
        </form>

        @if (isset($errors) && $errors->any())
            <div class="profession-notice profession-notice--error" role="alert">{{ $errors->first() }}</div>
        @endif

        @if ($events->isEmpty())
            <div class="profession-empty">
                <x-app.icon name="calendar" />
                <h2>Tidak ada event yang ditemukan</h2>
                <p>{{ request()->filled('keyword') || request()->filled('category') || request()->filled('status') ? 'Coba kata kunci atau filter yang lain.' : 'Mulai dengan menambahkan informasi kegiatan karier pertama.' }}</p>
            </div>
        @else
            <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel Career Event">
                <table class="profession-table">
                    <caption class="sr-only">Daftar Career Event tersimpan</caption>
                    <thead>
                        <tr>
                            <th scope="col">Event & Penyelenggara</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Jadwal & Waktu</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        @if ($event->poster_path)
                                            <a href="{{ route('admin.events.show', $event) }}" style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 6px; overflow: hidden; border: 1px solid var(--border); display: block;" title="Lihat detail poster">
                                                <img src="{{ route('admin.events.poster', $event) }}?v={{ $event->updated_at?->timestamp }}" alt="Poster {{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            </a>
                                        @else
                                            <div style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 6px; background: #f0f4f1; border: 1px dashed #d5ded5; display: flex; align-items: center; justify-content: center; color: var(--muted);" title="Belum ada poster">
                                                <x-app.icon name="calendar" style="width: 18px; height: 18px;" />
                                            </div>
                                        @endif
                                        <div style="min-width: 0;">
                                            <a class="profession-name" href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                                            <p class="event-meta-sub">{{ $event->organizer }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="event-badge-category">{{ $event->category }}</span>
                                </td>
                                <td>
                                    <span class="date-cell">{{ $event->start_at->format('d M Y, H:i') }} WIB</span>
                                    @if ($event->end_at)
                                        <p class="event-meta-sub">s/d {{ $event->end_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 11px; color: #344d43;">{{ Str::limit($event->location, 30) }}</span>
                                </td>
                                <td>
                                    @if ($event->status === 'published')
                                        <span class="event-status-pill event-status--published"><span></span> Terbit</span>
                                    @else
                                        <span class="event-status-pill event-status--draft"><span></span> Draf</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="text-link" href="{{ route('admin.events.show', $event) }}">Detail<span class="sr-only"> {{ $event->title }}</span></a>
                                        <a class="text-link" href="{{ route('admin.events.edit', $event) }}">Edit<span class="sr-only"> {{ $event->title }}</span></a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event \'{{ addslashes($event->title) }}\'? Data dan poster akan dihapus permanen.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-link text-link--muted" type="submit" style="color: #b5584b; cursor: pointer; background: none; border: none; font-size: inherit; padding: 0;">
                                                Hapus<span class="sr-only"> {{ $event->title }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="profession-pagination">
                <span>Menampilkan {{ $events->firstItem() }}–{{ $events->lastItem() }} dari {{ $events->total() }} event</span>
                <nav aria-label="Pagination event">
                    @if ($events->onFirstPage())
                        <span class="button button--small button--outline" aria-disabled="true">Sebelumnya</span>
                    @else
                        <a class="button button--small button--outline" href="{{ $events->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    <span>Halaman {{ $events->currentPage() }} / {{ $events->lastPage() }}</span>
                    @if ($events->hasMorePages())
                        <a class="button button--small button--outline" href="{{ $events->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="button button--small button--outline" aria-disabled="true">Berikutnya</span>
                    @endif
                </nav>
            </div>
        @endif
    </section>
</div>
@endsection
