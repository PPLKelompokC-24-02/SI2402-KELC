@extends('layouts.app')
@section('title', 'Career Event — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/careers.css') }}?v={{ filemtime(public_path('css/careers.css')) }}">
<link rel="stylesheet" href="{{ asset('css/events.css') }}?v={{ filemtime(public_path('css/events.css')) }}">
@endpush

@section('content')
<div class="career-page">

    {{-- ===== HERO BANNER ===== --}}
    @php
        $publishedCount = \App\Models\CareerEvent::where('status', 'published')->count();
        $categories     = \App\Models\CareerEvent::CATEGORIES;
    @endphp
    <section class="event-hero" aria-labelledby="event-hero-title">

        {{-- Noise/grain overlay --}}
        <div class="event-hero-bg-shapes" aria-hidden="true">
            <div class="event-hero-shape event-hero-shape--1"></div>
            <div class="event-hero-shape event-hero-shape--2"></div>
            <div class="event-hero-shape event-hero-shape--3"></div>
        </div>

        <div class="event-hero-inner">
            {{-- Kiri: Copy --}}
            <div class="event-hero-copy">
                <p class="event-hero-eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 11h18"/></svg>
                    CAREER EVENT
                </p>
                <h1 id="event-hero-title">
                    Temukan kegiatan<br>
                    <em>yang tepat untukmu.</em>
                </h1>
                <p class="event-hero-desc">
                    Jelajahi webinar, workshop, seminar, career fair, dan sertifikasi yang mendukung perjalanan kariermu. Semua event terkurasi khusus untuk mahasiswa.
                </p>

                {{-- Kategori Pills --}}
                <div class="event-hero-pills" aria-label="Kategori tersedia">
                    @foreach ($categories as $cat)
                        <a href="{{ route('events.index', ['category' => $cat]) }}"
                           class="event-hero-pill {{ $currentCategory === $cat ? 'event-hero-pill--active' : '' }}"
                           aria-label="Filter: {{ $cat }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: Stats cards + dekorasi --}}
            <div class="event-hero-right" aria-hidden="true">
                <div class="event-hero-art-ring">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path d="M7 3v4m10-4v4M3 11h18m-14 4h2m4 0h2"/>
                    </svg>
                </div>
                <p class="event-hero-art-label">Setiap event, satu peluang.</p>
            </div>
        </div>

        {{-- Stats bar di bawah --}}
        @if ($publishedCount > 0)
        <div class="event-hero-stats-bar">
            <div class="event-hero-stat-item">
                <span class="event-hero-stat-num">{{ $publishedCount }}</span>
                <span class="event-hero-stat-desc">Event aktif tersedia</span>
            </div>
            <div class="event-hero-stat-sep" aria-hidden="true"></div>
            <div class="event-hero-stat-item">
                <span class="event-hero-stat-num">{{ count($categories) }}</span>
                <span class="event-hero-stat-desc">Kategori kegiatan</span>
            </div>
            <div class="event-hero-stat-sep" aria-hidden="true"></div>
            <div class="event-hero-stat-item">
                <span class="event-hero-stat-num">100%</span>
                <span class="event-hero-stat-desc">Gratis untuk mahasiswa</span>
            </div>
        </div>
        @endif
    </section>

    {{-- ===== SEARCH BAR OVERLAY ===== --}}
    <div class="event-search-overlay">
        <form class="event-search-bar" method="GET" action="{{ route('events.index') }}" role="search" aria-label="Cari Career Event">
            {{-- Input keyword --}}
            <label class="sr-only" for="ev-keyword">Cari event berdasarkan judul, penyelenggara, atau lokasi</label>
            <x-app.icon name="search" class="search-icon" />
            <input
                id="ev-keyword"
                type="search"
                name="keyword"
                value="{{ $currentKeyword }}"
                placeholder="Cari event, penyelenggara, atau lokasi…"
                maxlength="150"
                autocomplete="off"
            >

            {{-- Divider --}}
            <div class="event-search-bar-divider" aria-hidden="true"></div>

            {{-- Filter kategori --}}
            <div class="event-search-bar-category">
                <label class="sr-only" for="ev-category">Filter kategori</label>
                <select id="ev-category" name="category" aria-label="Filter kategori event">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($currentCategory === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol aksi --}}
            <div class="event-search-bar-actions">
                <button class="career-button career-button--primary" type="submit">
                    Cari <x-arrow />
                </button>
                @if ($currentKeyword || $currentCategory)
                    <a class="event-reset-link" href="{{ route('events.index') }}" aria-label="Hapus semua filter">
                        <x-app.icon name="close" style="width: 12px; height: 12px;" />
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ===== CHIP FILTER AKTIF ===== --}}
    @if ($currentKeyword || $currentCategory)
        <div class="event-active-filters" role="status" aria-live="polite">
            <span style="font-size: 11px; color: var(--muted);">Filter aktif:</span>
            @if ($currentKeyword)
                <span class="event-filter-chip">
                    <x-app.icon name="search" />
                    {{ $currentKeyword }}
                </span>
            @endif
            @if ($currentCategory)
                <span class="event-filter-chip">
                    <x-app.icon name="briefcase" />
                    {{ $currentCategory }}
                </span>
            @endif
        </div>
    @endif

    {{-- ===== HEADER LIST ===== --}}
    <div class="event-list-header">
        <h2 class="event-list-title">
            @if ($currentKeyword || $currentCategory)
                Hasil Pencarian
            @else
                Event Tersedia
            @endif
        </h2>
        @if ($events->total())
            <span class="event-results-count">{{ $events->total() }} event ditemukan</span>
        @endif
    </div>

    {{-- ===== GRID EVENT atau EMPTY STATE ===== --}}
    @if ($events->isEmpty())
        {{-- AC-07: Empty State --}}
        <div class="event-empty" role="status">
            <div class="event-empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35M11 8v6M8 11h6"/>
                </svg>
            </div>
            <h3>Event tidak ditemukan</h3>
            @if ($currentKeyword || $currentCategory)
                <p>Tidak ada Career Event yang sesuai dengan pencarian atau filter yang kamu pilih.<br>Coba ubah kata kunci atau pilih kategori lain.</p>
                <a class="career-button career-button--outline" href="{{ route('events.index') }}">
                    Reset Pencarian
                </a>
            @else
                <p>Belum ada Career Event yang tersedia saat ini. Pantau terus halaman ini untuk menemukan kegiatan terbaru.</p>
            @endif
        </div>
    @else
        {{-- AC-01: Grid Kartu Event --}}
        <div class="event-card-grid" aria-label="Daftar Career Event">
            @foreach ($events as $event)
                <article class="event-card" aria-labelledby="ev-title-{{ $event->id }}">

                    {{-- Area Poster --}}
                    <a href="{{ route('events.show', $event) }}" class="event-card-poster" tabindex="-1" aria-hidden="true">
                        @if ($event->poster_path)
                            <img
                                src="{{ route('events.poster', $event) }}?v={{ $event->updated_at?->timestamp }}"
                                alt="Poster {{ $event->title }}"
                                loading="lazy"
                            >
                        @else
                            <div class="event-card-poster-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 11h18m-14 4h2m4 0h2"/>
                                </svg>
                                <span>Poster belum tersedia</span>
                            </div>
                        @endif
                        <span class="event-category-badge">{{ $event->category }}</span>
                    </a>

                    {{-- Body --}}
                    <div class="event-card-body">
                        <h3 class="event-card-title" id="ev-title-{{ $event->id }}">
                            <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                        </h3>
                        <div class="event-card-meta">
                            <div class="event-card-meta-item">
                                <x-app.icon name="calendar" />
                                <span>{{ $event->start_at->translatedFormat('D, d M Y · H:i') }} WIB</span>
                            </div>
                            <div class="event-card-meta-item">
                                <x-app.icon name="map-pin" />
                                <span>{{ Str::limit($event->location, 50) }}</span>
                            </div>
                            <div class="event-card-meta-item">
                                <x-app.icon name="user" />
                                <span>{{ Str::limit($event->organizer, 50) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="event-card-footer">
                        <a class="event-card-cta" href="{{ route('events.show', $event) }}" aria-label="Lihat detail {{ $event->title }}">
                            Lihat Detail <x-arrow />
                        </a>
                        @if ($event->registration_url)
                            <a
                                href="{{ $event->registration_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="event-card-register-btn"
                                aria-label="Daftar ke {{ $event->title }} (dibuka di tab baru)"
                            >
                                Daftar
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($events->hasPages())
            <nav class="career-pagination" aria-label="Pagination daftar Career Event" style="margin-top: 36px;">
                @if ($events->onFirstPage())
                    <span class="career-button career-button--outline" aria-disabled="true">Sebelumnya</span>
                @else
                    <a class="career-button career-button--outline" href="{{ $events->previousPageUrl() }}">Sebelumnya</a>
                @endif

                <span class="event-pagination-info">Halaman {{ $events->currentPage() }} dari {{ $events->lastPage() }}</span>

                @if ($events->hasMorePages())
                    <a class="career-button career-button--outline" href="{{ $events->nextPageUrl() }}">Berikutnya <x-arrow /></a>
                @else
                    <span class="career-button career-button--outline" aria-disabled="true">Berikutnya</span>
                @endif
            </nav>
        @endif
    @endif
</div>
@endsection
