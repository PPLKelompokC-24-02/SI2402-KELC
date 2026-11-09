@extends('layouts.app')
@section('title', $event->title . ' — Career Event — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/careers.css') }}?v={{ filemtime(public_path('css/careers.css')) }}">
<link rel="stylesheet" href="{{ asset('css/events.css') }}?v={{ filemtime(public_path('css/events.css')) }}">
@endpush

@section('content')
<div class="career-page">

    {{-- Navigasi Kembali --}}
    <a class="career-back" href="{{ route('events.index') }}">
        <x-app.icon name="back" /> Kembali ke Career Event
    </a>

    {{-- ===== HERO DETAIL ===== --}}
    <div class="event-detail-hero">
        <div class="event-detail-hero-left">
            <span class="event-category-pill">
                <x-app.icon name="briefcase" />
                {{ $event->category }}
            </span>
            <h1 class="event-detail-hero-title">{{ $event->title }}</h1>
            <p class="event-detail-hero-organizer">
                Diselenggarakan oleh <strong>{{ $event->organizer }}</strong>
            </p>

            {{-- Quick info bar --}}
            <div class="event-detail-quick-info">
                <div class="event-detail-quick-item">
                    <x-app.icon name="calendar" />
                    <span>{{ $event->start_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
                <div class="event-detail-quick-sep" aria-hidden="true"></div>
                <div class="event-detail-quick-item">
                    <x-app.icon name="map-pin" />
                    <span>{{ Str::limit($event->location, 60) }}</span>
                </div>
            </div>
        </div>

        @if ($event->registration_url)
        <div class="event-detail-hero-cta">
            <a
                class="event-detail-cta-btn"
                href="{{ $event->registration_url }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Buka tautan pendaftaran resmi {{ $event->title }} (dibuka di tab baru)"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Daftar Sekarang
            </a>
            <p class="event-detail-cta-note">Diarahkan ke website penyelenggara</p>
        </div>
        @endif
    </div>

    {{-- ===== LAYOUT DUA KOLOM ===== --}}
    <div class="event-detail-layout">

        {{-- ============ KOLOM KIRI ============ --}}
        <div>

            {{-- Kartu: Deskripsi --}}
            <div class="event-detail-card">
                <div class="event-detail-card-header">
                    <x-app.icon name="document" />
                    <h2>Deskripsi Kegiatan</h2>
                </div>
                <div class="event-description">{{ $event->description }}</div>
            </div>

            {{-- AC-06: Kotak Pendaftaran --}}
            @if ($event->registration_url)
                <div class="event-registration-box">
                    <div class="event-registration-box-inner">
                        <div class="event-registration-box-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </div>
                        <div>
                            <p class="event-registration-box-label">Informasi Pendaftaran</p>
                            <p class="event-registration-box-desc">
                                Pendaftaran dilakukan melalui website resmi penyelenggara.<br>
                                Career Compass tidak memproses pendaftaran secara internal.
                            </p>
                        </div>
                    </div>
                    {{-- AC-08: Hanya link eksternal, bukan form internal --}}
                    <a
                        class="career-button career-button--primary event-registration-btn"
                        href="{{ $event->registration_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Buka tautan pendaftaran resmi {{ $event->title }} (dibuka di tab baru)"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:15px;height:15px"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Buka Tautan Pendaftaran
                    </a>
                </div>
            @else
                <div class="event-no-registration">
                    <x-app.icon name="calendar" />
                    <span>Informasi pendaftaran belum tersedia. Silakan hubungi penyelenggara secara langsung untuk informasi lebih lanjut.</span>
                </div>
            @endif

        </div>

        {{-- ============ KOLOM KANAN ============ --}}
        <div class="event-detail-sidebar">

            {{-- Kartu: Poster --}}
            @if ($event->poster_path)
                <div class="event-poster-frame">
                    <img
                        src="{{ route('events.poster', $event) }}?v={{ $event->updated_at?->timestamp }}"
                        alt="Poster {{ $event->title }}"
                    >
                </div>
            @else
                <div class="event-poster-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 11h18m-14 4h2m4 0h2"/>
                    </svg>
                    <p>Poster kegiatan belum tersedia.</p>
                </div>
            @endif

            {{-- Kartu: Jadwal & Lokasi --}}
            <div class="event-detail-card" style="margin-top: 20px;">
                <div class="event-detail-card-header">
                    <x-app.icon name="calendar" />
                    <h2>Jadwal & Lokasi</h2>
                </div>
                <div class="event-info-list">

                    {{-- Waktu Mulai --}}
                    <div class="event-info-item">
                        <div class="event-info-icon-wrap">
                            <x-app.icon name="calendar" />
                        </div>
                        <div class="event-info-item-content">
                            <span class="event-info-label">Waktu Mulai</span>
                            <span class="event-info-value">
                                {{ $event->start_at->translatedFormat('l, d F Y') }}<br>
                                {{ $event->start_at->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    {{-- Waktu Selesai (opsional) --}}
                    @if ($event->end_at)
                        <div class="event-info-item">
                            <div class="event-info-icon-wrap">
                                <x-app.icon name="check" />
                            </div>
                            <div class="event-info-item-content">
                                <span class="event-info-label">Waktu Selesai</span>
                                <span class="event-info-value">
                                    {{ $event->end_at->translatedFormat('l, d F Y') }}<br>
                                    {{ $event->end_at->format('H:i') }} WIB
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Lokasi --}}
                    <div class="event-info-item">
                        <div class="event-info-icon-wrap">
                            <x-app.icon name="map-pin" />
                        </div>
                        <div class="event-info-item-content">
                            <span class="event-info-label">Lokasi</span>
                            <span class="event-info-value">{{ $event->location }}</span>
                        </div>
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="event-info-item">
                        <div class="event-info-icon-wrap">
                            <x-app.icon name="user" />
                        </div>
                        <div class="event-info-item-content">
                            <span class="event-info-label">Penyelenggara</span>
                            <span class="event-info-value">{{ $event->organizer }}</span>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="event-info-item">
                        <div class="event-info-icon-wrap">
                            <x-app.icon name="briefcase" />
                        </div>
                        <div class="event-info-item-content">
                            <span class="event-info-label">Kategori</span>
                            <span class="event-info-value">{{ $event->category }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
