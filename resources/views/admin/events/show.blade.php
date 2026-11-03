@extends('layouts.app')
@section('title', $event->title . ' — Detail Career Event')

@push('head')
<link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
<style>
    .event-detail-layout { display: grid; grid-template-columns: minmax(0, 1.8fr) minmax(280px, 1fr); gap: 24px; align-items: start; }
    @media (max-width: 900px) { .event-detail-layout { grid-template-columns: minmax(0, 1fr); } }
    .detail-card { padding: 26px; border: 1px solid var(--border); border-radius: 9px; background: white; margin-bottom: 22px; }
    .detail-card h2 { margin: 0 0 16px; color: var(--green); font-size: 16px; font-weight: 600; }
    .info-list { display: grid; gap: 14px; }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-item-label { color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500; }
    .info-item-value { color: #344d43; font-size: 13px; font-weight: 500; }
    .registration-box { padding: 20px; border-radius: 8px; background: #f0f6fa; border: 1px solid #d2e3ef; margin-top: 18px; }
    .registration-box p { margin: 0 0 12px; font-size: 12px; color: var(--navy); line-height: 1.6; }
    .poster-view { width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .poster-view img { display: block; width: 100%; height: auto; }
</style>
@endpush

@section('content')
<div class="profession-page">
    <a class="back-link" href="{{ route('admin.events.index') }}">
        <x-app.icon name="back" /> Kembali ke Manajemen Event
    </a>

    <div class="profession-heading">
        <div>
            <p class="profession-eyebrow">DETAIL CAREER EVENT</p>
            <h1>{{ $event->title }}</h1>
            <p>Penyelenggara: <strong>{{ $event->organizer }}</strong> &bull; Kategori: <strong>{{ $event->category }}</strong></p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div class="profession-status {{ $event->isPublished() ? 'profession-status--published' : 'profession-status--draft' }}">
                <span></span> {{ $event->isPublished() ? 'Terbit' : 'Draf' }}
            </div>
            <a class="button button--outline button--small" href="{{ route('admin.events.edit', $event) }}">
                Edit Event
            </a>
            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button class="button button--small" type="submit" style="color: #b5584b; border: 1px solid #eccbc6; background: #fff8f7; cursor: pointer;">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <p class="profession-notice profession-notice--success" role="status" style="margin-bottom: 20px;">{{ session('status') }}</p>
    @endif

    <div class="event-detail-layout">
        {{-- Kolom Kiri: Informasi Utama & Deskripsi --}}
        <div>
            <div class="detail-card">
                <h2>Deskripsi Kegiatan</h2>
                <div style="font-size: 13px; line-height: 1.8; color: #344d43; white-space: pre-line;">
                    {{ $event->description }}
                </div>

                {{-- Tautan Pendaftaran Resmi Eksternal (Sesuai Batasan PRD) --}}
                <div class="registration-box">
                    <p><strong>Tautan Pendaftaran Resmi Eksternal:</strong><br>Mahasiswa diarahkan ke formulir pendaftaran resmi penyelenggara di luar Career Compass.</p>
                    <a class="button button--primary button--small" 
                       href="{{ $event->registration_url }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       style="display: inline-flex;">
                        Buka Tautan Pendaftaran Resmi &rarr;
                    </a>
                </div>
            </div>

            <div class="detail-card">
                <h2>Jadwal & Lokasi</h2>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-item-label">Waktu Mulai</span>
                        <span class="info-item-value">{{ $event->start_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                    </div>
                    @if ($event->end_at)
                        <div class="info-item">
                            <span class="info-item-label">Waktu Selesai</span>
                            <span class="info-item-value">{{ $event->end_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <span class="info-item-label">Lokasi Kegiatan</span>
                        <span class="info-item-value">{{ $event->location }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Poster & Metadata --}}
        <div>
            <div class="detail-card">
                <h2>Poster Kegiatan</h2>
                @if ($event->poster_path)
                    <div class="poster-view">
                        <img src="{{ route('admin.events.poster', $event) }}?v={{ $event->updated_at?->timestamp }}" alt="Poster {{ $event->title }}">
                    </div>
                @else
                    <div style="text-align: center; padding: 30px; background: #f8faf7; border: 1px dashed #d5ded5; border-radius: 8px; color: var(--muted); font-size: 11px;">
                        <x-app.icon name="calendar" />
                        <p style="margin-top: 8px;">Event ini belum memiliki poster kegiatan.</p>
                        <a class="text-link" href="{{ route('admin.events.edit', $event) }}">Unggah poster sekarang</a>
                    </div>
                @endif
            </div>

            <div class="detail-card">
                <h2>Metadata</h2>
                <div class="info-list" style="font-size: 11px;">
                    <div class="info-item">
                        <span class="info-item-label">Status Visibilitas</span>
                        <span class="info-item-value">{{ $event->isPublished() ? 'Terbit (Dapat dilihat publik)' : 'Draf (Hanya Admin)' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Slug URL</span>
                        <span class="info-item-value" style="font-family: monospace; font-size: 11px;">{{ $event->slug }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Ditambahkan Pada</span>
                        <span class="info-item-value">{{ $event->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Terakhir Diperbarui</span>
                        <span class="info-item-value">{{ $event->updated_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

