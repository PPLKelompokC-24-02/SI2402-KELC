@extends('layouts.app')
@section('title', 'CV Builder — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
@endpush

@section('content')
<div class="cv-page">
    <p class="cv-eyebrow">PENGEMBANGAN KARIER</p>

    <div class="cv-heading">
        <div>
            <h1>CV Builder</h1>
            <p>Susun, kelola, dan perbarui data profil, pendidikan, keterampilan, pengalaman, proyek, dan sertifikasi Anda untuk menghasilkan CV profesional.</p>
        </div>
        <div class="cv-heading-actions">
            <a href="{{ route('cv.edit') }}" class="button button--primary">
                <x-app.icon name="edit" />
                <span>Edit Informasi CV</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="cv-notice cv-notice--success" role="status">
            <x-app.icon name="check" />
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <!-- Kartu Profil & Kontak Utama -->
    <article class="cv-profile-card">
        <div class="cv-profile-header">
            <div class="cv-profile-main">
                <h2 class="cv-name">{{ $cv->full_name }}</h2>
                @if ($cv->headline)
                    <div class="cv-headline">{{ $cv->headline }}</div>
                @endif

                <div class="cv-contacts-wrap">
                    @if ($cv->email)
                        <span class="cv-contact-chip">
                            <x-app.icon name="mail" />
                            <span>{{ $cv->email }}</span>
                        </span>
                    @endif
                    @if ($cv->phone)
                        <span class="cv-contact-chip">
                            <x-app.icon name="phone" />
                            <span>{{ $cv->phone }}</span>
                        </span>
                    @endif
                    @if ($cv->address)
                        <span class="cv-contact-chip">
                            <x-app.icon name="map-pin" />
                            <span>{{ $cv->address }}</span>
                        </span>
                    @endif
                    @if ($cv->linkedin_url)
                        <a href="{{ $cv->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="cv-contact-chip">
                            <x-app.icon name="external" />
                            <span>LinkedIn</span>
                        </a>
                    @endif
                    @if ($cv->portfolio_url)
                        <a href="{{ $cv->portfolio_url }}" target="_blank" rel="noopener noreferrer" class="cv-contact-chip">
                            <x-app.icon name="external" />
                            <span>Portofolio</span>
                        </a>
                    @endif
                    @if ($cv->github_url)
                        <a href="{{ $cv->github_url }}" target="_blank" rel="noopener noreferrer" class="cv-contact-chip">
                            <x-app.icon name="code" />
                            <span>GitHub</span>
                        </a>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('cv.edit') }}#section-profile" class="button button--outline button--small">
                    <x-app.icon name="edit" />
                    <span>Ubah Profil</span>
                </a>
            </div>
        </div>

        @if ($cv->summary)
            <div class="cv-summary-box">
                <h3 class="cv-summary-title">Ringkasan Profil</h3>
                <p class="cv-summary-text">{{ $cv->summary }}</p>
            </div>
        @endif
    </article>

    <!-- Grid Pendidikan & Keterampilan -->
    <div class="cv-grid cv-grid--two-column" style="margin-bottom: 24px;">
        <!-- Seksi Pendidikan -->
        <section class="cv-card" aria-labelledby="heading-education">
            <div class="cv-card-header">
                <h2 id="heading-education" class="cv-card-title">
                    <x-app.icon name="book" />
                    <span>Pendidikan</span>
                    <span class="cv-badge-count">{{ count($cv->getEducationsList()) }}</span>
                </h2>
                <a href="{{ route('cv.edit') }}#section-education" class="text-link">Kelola</a>
            </div>

            @if (empty($cv->getEducationsList()))
                <div class="cv-empty-section">
                    <p>Belum ada riwayat pendidikan yang ditambahkan.</p>
                    <a href="{{ route('cv.edit') }}#section-education" class="cv-empty-link">+ Tambah Pendidikan</a>
                </div>
            @else
                <div class="cv-item-list">
                    @foreach ($cv->getEducationsList() as $edu)
                        <article class="cv-item">
                            <div class="cv-item-header">
                                <div>
                                    <h3 class="cv-item-title">{{ $edu['institution'] ?? '-' }}</h3>
                                    <div class="cv-item-subtitle">
                                        {{ $edu['degree'] ?? '' }}{{ !empty($edu['degree']) && !empty($edu['field_of_study']) ? ' — ' : '' }}{{ $edu['field_of_study'] ?? '' }}
                                    </div>
                                </div>
                                <div class="cv-item-meta">
                                    @if (!empty($edu['start_year']) || !empty($edu['end_year']))
                                        <span class="cv-item-period">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Sekarang' }}</span>
                                    @endif
                                    @if (!empty($edu['gpa']))
                                        <span class="cv-pill cv-pill--gpa">IPK {{ $edu['gpa'] }}</span>
                                    @endif
                                </div>
                            </div>
                            @if (!empty($edu['description']))
                                <p class="cv-item-desc">{{ $edu['description'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Seksi Keterampilan (Skills) -->
        <section class="cv-card" aria-labelledby="heading-skills">
            <div class="cv-card-header">
                <h2 id="heading-skills" class="cv-card-title">
                    <x-app.icon name="compass" />
                    <span>Keterampilan</span>
                    <span class="cv-badge-count">{{ count($cv->getSkillsList()) }}</span>
                </h2>
                <a href="{{ route('cv.edit') }}#section-skills" class="text-link">Kelola</a>
            </div>

            @if (empty($cv->getSkillsList()))
                <div class="cv-empty-section">
                    <p>Belum ada kelompok keterampilan yang ditambahkan.</p>
                    <a href="{{ route('cv.edit') }}#section-skills" class="cv-empty-link">+ Tambah Keterampilan</a>
                </div>
            @else
                <div class="skill-category-group">
                    @foreach ($cv->getSkillsList() as $skillGroup)
                        <div class="skill-category-block">
                            <h3 class="skill-category-name">{{ $skillGroup['category'] ?? 'Keahlian' }}</h3>
                            <div class="skill-tags-wrap">
                                @php
                                    $items = array_map('trim', explode(',', $skillGroup['items'] ?? ''));
                                @endphp
                                @foreach ($items as $item)
                                    @if ($item !== '')
                                        <span class="skill-tag">{{ $item }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- Seksi Pengalaman (Full Width) -->
    <section class="cv-card" style="margin-bottom: 24px;" aria-labelledby="heading-experience">
        <div class="cv-card-header">
            <h2 id="heading-experience" class="cv-card-title">
                <x-app.icon name="briefcase" />
                <span>Pengalaman (Kerja, Magang, Organisasi)</span>
                <span class="cv-badge-count">{{ count($cv->getExperiencesList()) }}</span>
            </h2>
            <a href="{{ route('cv.edit') }}#section-experience" class="text-link">Kelola</a>
        </div>

        @if (empty($cv->getExperiencesList()))
            <div class="cv-empty-section">
                <p>Belum ada riwayat pengalaman yang ditambahkan.</p>
                <a href="{{ route('cv.edit') }}#section-experience" class="cv-empty-link">+ Tambah Pengalaman</a>
            </div>
        @else
            <div class="cv-item-list">
                @foreach ($cv->getExperiencesList() as $exp)
                    <article class="cv-item">
                        <div class="cv-item-header">
                            <div>
                                <h3 class="cv-item-title">{{ $exp['position'] ?? '-' }}</h3>
                                <div class="cv-item-subtitle">{{ $exp['company'] ?? '-' }}{{ !empty($exp['location']) ? ' • '.$exp['location'] : '' }}</div>
                            </div>
                            <div class="cv-item-meta">
                                <span class="cv-item-period">
                                    {{ $exp['start_date'] ?? '' }} - {{ !empty($exp['is_current']) ? 'Sekarang' : ($exp['end_date'] ?? 'Selesai') }}
                                </span>
                            </div>
                        </div>
                        @if (!empty($exp['description']))
                            <p class="cv-item-desc">{{ $exp['description'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Grid Proyek & Sertifikasi -->
    <div class="cv-grid cv-grid--two-column" style="margin-bottom: 24px;">
        <!-- Seksi Proyek -->
        <section class="cv-card" aria-labelledby="heading-projects">
            <div class="cv-card-header">
                <h2 id="heading-projects" class="cv-card-title">
                    <x-app.icon name="code" />
                    <span>Proyek &amp; Portofolio</span>
                    <span class="cv-badge-count">{{ count($cv->getProjectsList()) }}</span>
                </h2>
                <a href="{{ route('cv.edit') }}#section-projects" class="text-link">Kelola</a>
            </div>

            @if (empty($cv->getProjectsList()))
                <div class="cv-empty-section">
                    <p>Belum ada proyek yang ditambahkan.</p>
                    <a href="{{ route('cv.edit') }}#section-projects" class="cv-empty-link">+ Tambah Proyek</a>
                </div>
            @else
                <div class="cv-item-list">
                    @foreach ($cv->getProjectsList() as $proj)
                        <article class="cv-item">
                            <div class="cv-item-header">
                                <div>
                                    <h3 class="cv-item-title">{{ $proj['title'] ?? '-' }}</h3>
                                    @if (!empty($proj['role']))
                                        <div class="cv-item-subtitle">Peran: {{ $proj['role'] }}</div>
                                    @endif
                                </div>
                                @if (!empty($proj['period']))
                                    <span class="cv-item-period">{{ $proj['period'] }}</span>
                                @endif
                            </div>

                            @if (!empty($proj['technologies']))
                                <div style="margin-top: 6px;">
                                    <span class="cv-pill cv-pill--tech">{{ $proj['technologies'] }}</span>
                                </div>
                            @endif

                            @if (!empty($proj['description']))
                                <p class="cv-item-desc">{{ $proj['description'] }}</p>
                            @endif

                            @if (!empty($proj['url']))
                                <a href="{{ $proj['url'] }}" target="_blank" rel="noopener noreferrer" class="cv-item-link">
                                    <x-app.icon name="external" />
                                    <span>Lihat Proyek</span>
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Seksi Sertifikasi -->
        <section class="cv-card" aria-labelledby="heading-certifications">
            <div class="cv-card-header">
                <h2 id="heading-certifications" class="cv-card-title">
                    <x-app.icon name="award" />
                    <span>Sertifikasi &amp; Lisensi</span>
                    <span class="cv-badge-count">{{ count($cv->getCertificationsList()) }}</span>
                </h2>
                <a href="{{ route('cv.edit') }}#section-certifications" class="text-link">Kelola</a>
            </div>

            @if (empty($cv->getCertificationsList()))
                <div class="cv-empty-section">
                    <p>Belum ada sertifikasi yang ditambahkan.</p>
                    <a href="{{ route('cv.edit') }}#section-certifications" class="cv-empty-link">+ Tambah Sertifikasi</a>
                </div>
            @else
                <div class="cv-item-list">
                    @foreach ($cv->getCertificationsList() as $cert)
                        <article class="cv-item">
                            <div class="cv-item-header">
                                <div>
                                    <h3 class="cv-item-title">{{ $cert['name'] ?? '-' }}</h3>
                                    @if (!empty($cert['issuer']))
                                        <div class="cv-item-subtitle">{{ $cert['issuer'] }}</div>
                                    @endif
                                </div>
                                @if (!empty($cert['issue_date']))
                                    <span class="cv-item-period">{{ $cert['issue_date'] }}</span>
                                @endif
                            </div>

                            @if (!empty($cert['credential_id']))
                                <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">
                                    ID Kredensial: <strong>{{ $cert['credential_id'] }}</strong>
                                </div>
                            @endif

                            @if (!empty($cert['credential_url']))
                                <a href="{{ $cert['credential_url'] }}" target="_blank" rel="noopener noreferrer" class="cv-item-link">
                                    <x-app.icon name="external" />
                                    <span>Tampilkan Kredensial</span>
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- Banner Ajak Edit -->
    <div class="cv-cta-banner">
        <div class="cv-cta-content">
            <h3>Ingin Memperbarui atau Melengkapi Data CV Anda?</h3>
            <p>Pastikan seluruh informasi profil, pendidikan, riwayat pengalaman, dan keahlian Anda selalu up-to-date untuk memudahkan proses lamaran magang atau pekerjaan.</p>
        </div>
        <div>
            <a href="{{ route('cv.edit') }}" class="button button--primary">
                <x-app.icon name="edit" />
                <span>Edit Informasi CV Mahasiswa</span>
            </a>
        </div>
    </div>
</div>
@endsection
