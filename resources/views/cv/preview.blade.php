@extends('layouts.app')
@section('title', 'Pratinjau CV ATS — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
@endpush

@section('content')
<div class="cv-page">
    <div class="preview-toolbar">
        <div>
            <a href="{{ route('cv.index') }}" class="back-link" style="margin-bottom:0;">
                <x-app.icon name="back" />
                <span>Kembali ke CV Builder</span>
            </a>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('cv.edit') }}" class="button button--outline">
                <x-app.icon name="edit" />
                <span>Edit Informasi CV</span>
            </a>
            <button type="button" class="button button--primary" onclick="window.print()">
                <x-app.icon name="download" />
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Paper Sheet ATS View -->
    <div class="ats-paper" id="cv-paper">
        <!-- ATS Header -->
        <header class="ats-header">
            <h1 class="ats-name">{{ $cv->full_name }}</h1>
            @if ($cv->headline)
                <div class="ats-title">{{ $cv->headline }}</div>
            @endif
            <div class="ats-contact-line">
                @if ($cv->phone)
                    <span class="ats-contact-item">{{ $cv->phone }}</span>
                @endif
                @if ($cv->email)
                    <span class="ats-contact-item">{{ $cv->email }}</span>
                @endif
                @if ($cv->address)
                    <span class="ats-contact-item">{{ $cv->address }}</span>
                @endif
                @if ($cv->linkedin_url)
                    <span class="ats-contact-item">
                        <a href="{{ $cv->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                    </span>
                @endif
                @if ($cv->portfolio_url)
                    <span class="ats-contact-item">
                        <a href="{{ $cv->portfolio_url }}" target="_blank" rel="noopener noreferrer">Portofolio</a>
                    </span>
                @endif
                @if ($cv->github_url)
                    <span class="ats-contact-item">
                        <a href="{{ $cv->github_url }}" target="_blank" rel="noopener noreferrer">GitHub</a>
                    </span>
                @endif
            </div>
        </header>

        <!-- Ringkasan Profesional -->
        @if ($cv->summary)
            <section class="ats-section">
                <h2 class="ats-section-title">Ringkasan Profesional</h2>
                <p class="ats-entry-desc" style="font-size:12px;line-height:1.7;">{{ $cv->summary }}</p>
            </section>
        @endif

        <!-- Riwayat Pendidikan -->
        @if (!empty($cv->getEducationsList()))
            <section class="ats-section">
                <h2 class="ats-section-title">Pendidikan</h2>
                @foreach ($cv->getEducationsList() as $edu)
                    <div class="ats-entry">
                        <div class="ats-entry-header">
                            <div>
                                <span class="ats-entry-title">{{ $edu['institution'] ?? '-' }}</span>
                                @if (!empty($edu['degree']) || !empty($edu['field_of_study']))
                                    <span class="ats-entry-institution"> — {{ $edu['degree'] ?? '' }}{{ !empty($edu['degree']) && !empty($edu['field_of_study']) ? ', ' : '' }}{{ $edu['field_of_study'] ?? '' }}</span>
                                @endif
                            </div>
                            <div class="ats-entry-date">
                                {{ $edu['start_year'] ?? '' }}{{ !empty($edu['start_year']) && !empty($edu['end_year']) ? ' – ' : '' }}{{ $edu['end_year'] ?? '' }}
                            </div>
                        </div>
                        @if (!empty($edu['gpa']))
                            <div style="font-size:11.5px;color:#333;margin-top:2px;">
                                Indeks Prestasi Kumulatif (IPK): <strong>{{ $edu['gpa'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($edu['description']))
                            <p class="ats-entry-desc">{{ $edu['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Riwayat Pengalaman -->
        @if (!empty($cv->getExperiencesList()))
            <section class="ats-section">
                <h2 class="ats-section-title">Pengalaman Kerja &amp; Organisasi</h2>
                @foreach ($cv->getExperiencesList() as $exp)
                    <div class="ats-entry">
                        <div class="ats-entry-header">
                            <div>
                                <span class="ats-entry-title">{{ $exp['position'] ?? '-' }}</span>
                                <span class="ats-entry-institution"> — {{ $exp['company'] ?? '-' }}{{ !empty($exp['location']) ? ' ('.$exp['location'].')' : '' }}</span>
                            </div>
                            <div class="ats-entry-date">
                                {{ $exp['start_date'] ?? '' }}{{ !empty($exp['start_date']) ? ' – ' : '' }}{{ !empty($exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? '') }}
                            </div>
                        </div>
                        @if (!empty($exp['description']))
                            <p class="ats-entry-desc">{{ $exp['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Proyek -->
        @if (!empty($cv->getProjectsList()))
            <section class="ats-section">
                <h2 class="ats-section-title">Proyek &amp; Portofolio</h2>
                @foreach ($cv->getProjectsList() as $proj)
                    <div class="ats-entry">
                        <div class="ats-entry-header">
                            <div>
                                <span class="ats-entry-title">{{ $proj['title'] ?? '-' }}</span>
                                @if (!empty($proj['role']))
                                    <span class="ats-entry-institution"> ({{ $proj['role'] }})</span>
                                @endif
                            </div>
                            @if (!empty($proj['period']))
                                <div class="ats-entry-date">{{ $proj['period'] }}</div>
                            @endif
                        </div>
                        @if (!empty($proj['technologies']))
                            <div style="font-size:11.5px;color:#333;margin-top:2px;">
                                <em>Teknologi: {{ $proj['technologies'] }}</em>
                            </div>
                        @endif
                        @if (!empty($proj['description']))
                            <p class="ats-entry-desc">{{ $proj['description'] }}</p>
                        @endif
                        @if (!empty($proj['url']))
                            <div style="font-size:11px;margin-top:3px;">
                                <a href="{{ $proj['url'] }}" target="_blank" rel="noopener noreferrer">{{ $proj['url'] }}</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Keterampilan -->
        @if (!empty($cv->getSkillsList()))
            <section class="ats-section">
                <h2 class="ats-section-title">Keterampilan</h2>
                @foreach ($cv->getSkillsList() as $skillGroup)
                    <div class="ats-skills-line">
                        <strong>{{ $skillGroup['category'] ?? 'Keahlian' }}:</strong>
                        <span>{{ $skillGroup['items'] ?? '' }}</span>
                    </div>
                @endforeach
            </section>
        @endif

        <!-- Sertifikasi -->
        @if (!empty($cv->getCertificationsList()))
            <section class="ats-section">
                <h2 class="ats-section-title">Sertifikasi &amp; Lisensi</h2>
                @foreach ($cv->getCertificationsList() as $cert)
                    <div class="ats-entry">
                        <div class="ats-entry-header">
                            <div>
                                <span class="ats-entry-title">{{ $cert['name'] ?? '-' }}</span>
                                @if (!empty($cert['issuer']))
                                    <span class="ats-entry-institution"> — {{ $cert['issuer'] }}</span>
                                @endif
                            </div>
                            @if (!empty($cert['issue_date']))
                                <div class="ats-entry-date">{{ $cert['issue_date'] }}</div>
                            @endif
                        </div>
                        @if (!empty($cert['credential_id']))
                            <div style="font-size:11.5px;color:#444;margin-top:2px;">
                                ID Kredensial: {{ $cert['credential_id'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif
    </div>
</div>
@endsection
