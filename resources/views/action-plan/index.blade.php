@extends('layouts.app')
@section('title', 'Career Action Plan — Career Compass')
@push('head')
<link rel="stylesheet" href="{{ asset('css/action-templates.css') }}?v={{ filemtime(public_path('css/action-templates.css')) }}">
@endpush
@section('content')
<div class="template-page">
    <p class="template-eyebrow">PENGEMBANGAN KARIER</p>
    <div class="template-heading">
        <div>
            <h1>Career Action Plan</h1>
            <p>Susun dan kelola rencana kegiatan pengembangan kompetensi untuk mencapai tujuan karier Anda.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="template-notice template-notice--success" role="status">
            {{ session('status') }}
        </div>
    @endif

    <!-- Bagian 1: Referensi Template Master dari Admin (US-16 Integration Point) -->
    <section class="student-section" aria-labelledby="templates-heading">
        <div class="student-section-heading">
            <h2 id="templates-heading">Referensi Template Aktivitas</h2>
            <p>Pilih dan gunakan template aktivitas yang telah disiapkan oleh Admin sebagai acuan kegiatan pengembangan kompetensi Anda.</p>
        </div>

        @if ($templates->isEmpty())
            <div class="template-list-card">
                <div class="template-empty">
                    <x-app.icon name="plan" />
                    <h2>Belum ada template aktivitas aktif</h2>
                    <p>Saat ini belum ada template aktivitas yang tersedia dari Admin. Anda dapat kembali memeriksa beberapa saat lagi.</p>
                </div>
            </div>
        @else
            <div class="templates-grid">
                @foreach ($templates as $template)
                    <article class="template-item-card">
                        <div class="template-item-content">
                            <h3>{{ $template->title }}</h3>
                            <p>{{ $template->description }}</p>
                        </div>
                        <div class="template-item-footer">
                            <span class="template-status template-status--active">
                                <span></span>Tersedia
                            </span>
                            <form method="POST" action="{{ route('action-plan.from-template', $template) }}">
                                @csrf
                                <button class="button button--primary button--small" type="submit">
                                    <x-app.icon name="plus" /> Gunakan Template
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Bagian 2: Aktivitas Pribadi Mahasiswa (Menjamin Independensi Data Mahasiswa - AC-07) -->
    <section class="student-section" aria-labelledby="plans-heading">
        <div class="student-section-heading">
            <h2 id="plans-heading">Aktivitas Action Plan Saya</h2>
            <p>Daftar aktivitas pengembangan diri mandiri Anda. Data ini berdiri sendiri dan tidak terpengaruh oleh perubahan master template.</p>
        </div>

        <div class="template-list-card">
            @if ($actionPlans->isEmpty())
                <div class="template-empty">
                    <x-app.icon name="plan" />
                    <h2>Belum ada aktivitas di Career Action Plan</h2>
                    <p>Pilih salah satu template aktivitas di atas dengan mengklik "Gunakan Template" untuk menambahkan aktivitas pertama Anda.</p>
                </div>
            @else
                <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel aktivitas Career Action Plan saya">
                    <table class="action-plan-table">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 260px;">Aktivitas</th>
                                <th scope="col" style="min-width: 220px;">Deskripsi</th>
                                <th scope="col" style="width: 140px;">Status</th>
                                <th scope="col" style="width: 180px;">Sumber Referensi</th>
                                <th scope="col" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actionPlans as $plan)
                                <tr>
                                    <td>
                                        <strong style="color: var(--green); font-size: 13px;">{{ $plan->title }}</strong>
                                    </td>
                                    <td style="color: #52645c; font-size: 11px; line-height: 1.6;">
                                        {{ $plan->description ?: 'Tidak ada deskripsi.' }}
                                    </td>
                                    <td>
                                        <span class="status-pill">{{ $plan->status }}</span>
                                    </td>
                                    <td style="font-size: 11px; color: var(--muted);">
                                        @if ($plan->template)
                                            <span title="Dibuat dari master template aktif">{{ $plan->template->title }}</span>
                                        @else
                                            <em>Mandiri / Template Asal Dihapus</em>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('action-plan.destroy', $plan) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-link text-link--muted" type="submit" onclick="return confirm('Hapus aktivitas ini dari rencana aksi Anda?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

