@extends('layouts.app')
@section('title', 'Edit Informasi CV — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
<script src="{{ asset('js/cv-builder.js') }}?v={{ filemtime(public_path('js/cv-builder.js')) }}" defer></script>
@endpush

@section('content')
<div class="cv-page">
    <a href="{{ route('cv.index') }}" class="back-link">
        <x-app.icon name="back" />
        <span>Kembali ke CV Builder</span>
    </a>

    <p class="cv-eyebrow">PENGEMBANGAN KARIER</p>

    <div class="cv-heading">
        <div>
            <h1>Edit Informasi CV Mahasiswa</h1>
            <p>Perbarui informasi profil, kontak, riwayat pendidikan, keterampilan, pengalaman kerja/organisasi, proyek, dan sertifikasi Anda.</p>
        </div>
        <div class="cv-heading-actions">
            <a href="{{ route('cv.index') }}" class="button button--outline">Batal</a>
            <button type="submit" form="form-edit-cv" class="button button--primary">
                <x-app.icon name="check" />
                <span>Simpan Perubahan CV</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="cv-notice cv-notice--error" role="alert">
            <x-app.icon name="close" />
            <div>
                <strong>Terdapat kesalahan pengisian data:</strong>
                <ul style="margin: 6px 0 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Navigasi Lompat Seksi -->
    <nav class="cv-nav-pills" aria-label="Navigasi seksi form CV">
        <a href="#section-profile" class="cv-nav-pill is-active">1. Profil</a>
        <a href="#section-contact" class="cv-nav-pill">2. Kontak</a>
        <a href="#section-education" class="cv-nav-pill">3. Pendidikan</a>
        <a href="#section-skills" class="cv-nav-pill">4. Keterampilan</a>
        <a href="#section-experience" class="cv-nav-pill">5. Pengalaman</a>
        <a href="#section-projects" class="cv-nav-pill">6. Proyek</a>
        <a href="#section-certifications" class="cv-nav-pill">7. Sertifikasi</a>
    </nav>

    <form id="form-edit-cv" method="POST" action="{{ route('cv.update') }}">
        @csrf
        @method('PUT')

        <!-- 1. DATA PROFIL -->
        <section id="section-profile" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="user" />
                    <span>1. Data Profil</span>
                </h2>
            </div>

            <div class="form-grid-2">
                <div class="editor-field">
                    <label for="full_name">Nama Lengkap <span class="required-indicator">*</span></label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $cv->full_name) }}" required>
                    <p class="field-help">Nama lengkap yang akan ditampilkan di bagian paling atas CV.</p>
                </div>
                <div class="editor-field">
                    <label for="headline">Headline Profesional / Jabatan Impian</label>
                    <input type="text" id="headline" name="headline" class="form-control" value="{{ old('headline', $cv->headline) }}" placeholder="Contoh: Mahasiswa Sistem Informasi | Aspiring Product Manager">
                    <p class="field-help">Satu baris ringkas yang mendefinisikan fokus dan aspirasi karier Anda.</p>
                </div>
            </div>

            <div class="editor-field" style="margin-bottom: 0;">
                <label for="summary">Ringkasan Profil (Professional Summary)</label>
                <textarea id="summary" name="summary" class="form-control" style="min-height: 110px;" placeholder="Tuliskan 2-4 kalimat ringkasan tentang latar belakang pendidikan, minat utama, dan nilai tambah yang Anda tawarkan...">{{ old('summary', $cv->summary) }}</textarea>
                <p class="field-help">Ringkasan singkat yang menarik minat perekrut untuk membaca CV Anda lebih lanjut.</p>
            </div>
        </section>

        <!-- 2. INFORMASI KONTAK -->
        <section id="section-contact" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="mail" />
                    <span>2. Informasi Kontak</span>
                </h2>
            </div>

            <div class="form-grid-3">
                <div class="editor-field">
                    <label for="email">Email Kontak <span class="required-indicator">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $cv->email) }}" required>
                </div>
                <div class="editor-field">
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $cv->phone) }}" placeholder="Contoh: +62 812-3456-7890">
                </div>
                <div class="editor-field">
                    <label for="address">Domisili / Kota</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $cv->address) }}" placeholder="Contoh: Surabaya, Jawa Timur">
                </div>
            </div>

            <div class="form-grid-3">
                <div class="editor-field">
                    <label for="linkedin_url">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $cv->linkedin_url) }}" placeholder="https://linkedin.com/in/username">
                </div>
                <div class="editor-field">
                    <label for="portfolio_url">Portofolio / Website URL</label>
                    <input type="url" id="portfolio_url" name="portfolio_url" class="form-control" value="{{ old('portfolio_url', $cv->portfolio_url) }}" placeholder="https://portofolio-anda.com">
                </div>
                <div class="editor-field">
                    <label for="github_url">GitHub URL</label>
                    <input type="url" id="github_url" name="github_url" class="form-control" value="{{ old('github_url', $cv->github_url) }}" placeholder="https://github.com/username">
                </div>
            </div>
        </section>

        <!-- 3. RIWAYAT PENDIDIKAN -->
        <section id="section-education" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="book" />
                    <span>3. Riwayat Pendidikan</span>
                </h2>
            </div>

            <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
                Tambahkan riwayat pendidikan formal Anda, mulai dari pendidikan tingkat tinggi terkini atau perguruan tinggi Anda.
            </p>

            <div id="education-repeater" class="repeater-container" data-prefix="Riwayat Pendidikan">
                @php
                    $educations = old('educations', $cv->getEducationsList());
                @endphp
                @foreach ($educations as $idx => $edu)
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <h3 class="repeater-item-title" data-label="Riwayat Pendidikan">Riwayat Pendidikan #{{ $loop->iteration }}</h3>
                            <button type="button" class="btn-remove-row" aria-label="Hapus riwayat pendidikan ini">
                                <x-app.icon name="trash" />
                                <span>Hapus</span>
                            </button>
                        </div>
                        <div class="form-grid-2">
                            <div class="editor-field">
                                <label>Nama Institusi / Universitas <span class="required-indicator">*</span></label>
                                <input type="text" name="educations[{{ $idx }}][institution]" class="form-control" value="{{ $edu['institution'] ?? '' }}" placeholder="Contoh: Institut Teknologi Telkom Surabaya" required>
                            </div>
                            <div class="editor-field">
                                <label>Jenjang / Gelar</label>
                                <input type="text" name="educations[{{ $idx }}][degree]" class="form-control" value="{{ $edu['degree'] ?? '' }}" placeholder="Contoh: Sarjana (S1)">
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="editor-field">
                                <label>Jurusan / Bidang Studi</label>
                                <input type="text" name="educations[{{ $idx }}][field_of_study]" class="form-control" value="{{ $edu['field_of_study'] ?? '' }}" placeholder="Contoh: Sistem Informasi">
                            </div>
                            <div class="editor-field">
                                <label>Tahun Masuk - Lulus</label>
                                <div style="display:flex;gap:10px;">
                                    <input type="text" name="educations[{{ $idx }}][start_year]" class="form-control" style="flex:1;" value="{{ $edu['start_year'] ?? '' }}" placeholder="Mulai (2022)">
                                    <input type="text" name="educations[{{ $idx }}][end_year]" class="form-control" style="flex:1;" value="{{ $edu['end_year'] ?? '' }}" placeholder="Lulus (2026)">
                                </div>
                            </div>
                            <div class="editor-field">
                                <label>IPK / Nilai Kelulusan</label>
                                <input type="text" name="educations[{{ $idx }}][gpa]" class="form-control" value="{{ $edu['gpa'] ?? '' }}" placeholder="Contoh: 3.82">
                            </div>
                        </div>
                        <div class="editor-field" style="margin-bottom: 0;">
                            <label>Deskripsi Tambahan / Prestasi Akademik</label>
                            <textarea name="educations[{{ $idx }}][description]" class="form-control" style="min-height: 70px;" placeholder="Fokus konsentrasi, penelitian, atau penghargaan akademik...">{{ $edu['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="btn-add-education" class="btn-add-row">
                <x-app.icon name="plus" />
                <span>Tambah Riwayat Pendidikan</span>
            </button>
        </section>

        <!-- 4. KETERAMPILAN (SKILLS) -->
        <section id="section-skills" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="compass" />
                    <span>4. Keterampilan &amp; Keahlian</span>
                </h2>
            </div>

            <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
                Kelompokkan keterampilan Anda berdasarkan bidang (mis. Keahlian Teknis, Tools/Software, Soft Skills) dan sebutkan daftarnya dipisahkan koma.
            </p>

            <div id="skills-repeater" class="repeater-container" data-prefix="Kelompok Keterampilan">
                @php
                    $skills = old('skills', $cv->getSkillsList());
                @endphp
                @foreach ($skills as $idx => $skillGroup)
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <h3 class="repeater-item-title" data-label="Kelompok Keterampilan">Kelompok Keterampilan #{{ $loop->iteration }}</h3>
                            <button type="button" class="btn-remove-row" aria-label="Hapus kelompok keterampilan ini">
                                <x-app.icon name="trash" />
                                <span>Hapus</span>
                            </button>
                        </div>
                        <div class="form-grid-2">
                            <div class="editor-field">
                                <label>Nama Kategori Keterampilan <span class="required-indicator">*</span></label>
                                <input type="text" name="skills[{{ $idx }}][category]" class="form-control" value="{{ $skillGroup['category'] ?? '' }}" placeholder="Contoh: Bahasa Pemrograman, Tools Desain, Soft Skills" required>
                            </div>
                            <div class="editor-field">
                                <label>Daftar Keterampilan (Pisahkan dengan koma) <span class="required-indicator">*</span></label>
                                <input type="text" name="skills[{{ $idx }}][items]" class="form-control" value="{{ $skillGroup['items'] ?? '' }}" placeholder="Contoh: PHP, Laravel, MySQL, Git, REST API" required>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="btn-add-skill" class="btn-add-row">
                <x-app.icon name="plus" />
                <span>Tambah Kelompok Keterampilan</span>
            </button>
        </section>

        <!-- 5. PENGALAMAN -->
        <section id="section-experience" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="briefcase" />
                    <span>5. Pengalaman (Kerja, Magang, Organisasi)</span>
                </h2>
            </div>

            <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
                Cantumkan riwayat pengalaman magang, pekerjaan paruh waktu, proyek lepas, maupun aktivitas organisasi kampus yang relevan.
            </p>

            <div id="experience-repeater" class="repeater-container" data-prefix="Pengalaman">
                @php
                    $experiences = old('experiences', $cv->getExperiencesList());
                @endphp
                @foreach ($experiences as $idx => $exp)
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <h3 class="repeater-item-title" data-label="Pengalaman">Pengalaman #{{ $loop->iteration }}</h3>
                            <button type="button" class="btn-remove-row" aria-label="Hapus pengalaman ini">
                                <x-app.icon name="trash" />
                                <span>Hapus</span>
                            </button>
                        </div>
                        <div class="form-grid-2">
                            <div class="editor-field">
                                <label>Posisi / Peran <span class="required-indicator">*</span></label>
                                <input type="text" name="experiences[{{ $idx }}][position]" class="form-control" value="{{ $exp['position'] ?? '' }}" placeholder="Contoh: Business Analyst Intern" required>
                            </div>
                            <div class="editor-field">
                                <label>Nama Perusahaan / Organisasi <span class="required-indicator">*</span></label>
                                <input type="text" name="experiences[{{ $idx }}][company]" class="form-control" value="{{ $exp['company'] ?? '' }}" placeholder="Contoh: Himpunan Mahasiswa / PT Contoh Indonesia" required>
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="editor-field">
                                <label>Lokasi</label>
                                <input type="text" name="experiences[{{ $idx }}][location]" class="form-control" value="{{ $exp['location'] ?? '' }}" placeholder="Contoh: Surabaya / Remote">
                            </div>
                            <div class="editor-field">
                                <label>Periode Mulai</label>
                                <input type="text" name="experiences[{{ $idx }}][start_date]" class="form-control" value="{{ $exp['start_date'] ?? '' }}" placeholder="Contoh: Agu 2023">
                            </div>
                            <div class="editor-field">
                                <label>Periode Selesai</label>
                                <input type="text" name="experiences[{{ $idx }}][end_date]" class="form-control" value="{{ $exp['end_date'] ?? '' }}" placeholder="Contoh: Jul 2024 / Sekarang">
                            </div>
                        </div>
                        <div class="editor-field" style="margin-bottom: 0;">
                            <label>Deskripsi Tugas, Kontribusi & Hasil Nyata</label>
                            <textarea name="experiences[{{ $idx }}][description]" class="form-control" style="min-height: 80px;" placeholder="Uraikan tanggung jawab dan pencapaian terukur yang Anda peroleh selama menjalani peran tersebut...">{{ $exp['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="btn-add-experience" class="btn-add-row">
                <x-app.icon name="plus" />
                <span>Tambah Pengalaman</span>
            </button>
        </section>

        <!-- 6. PROYEK & PORTOFOLIO -->
        <section id="section-projects" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="code" />
                    <span>6. Proyek &amp; Portofolio</span>
                </h2>
            </div>

            <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
                Tampilkan proyek mata kuliah, capstone project, atau proyek mandiri yang mendemonstrasikan keahlian terapan Anda.
            </p>

            <div id="project-repeater" class="repeater-container" data-prefix="Proyek">
                @php
                    $projects = old('projects', $cv->getProjectsList());
                @endphp
                @foreach ($projects as $idx => $proj)
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <h3 class="repeater-item-title" data-label="Proyek">Proyek #{{ $loop->iteration }}</h3>
                            <button type="button" class="btn-remove-row" aria-label="Hapus proyek ini">
                                <x-app.icon name="trash" />
                                <span>Hapus</span>
                            </button>
                        </div>
                        <div class="form-grid-2">
                            <div class="editor-field">
                                <label>Nama Proyek <span class="required-indicator">*</span></label>
                                <input type="text" name="projects[{{ $idx }}][title]" class="form-control" value="{{ $proj['title'] ?? '' }}" placeholder="Contoh: Sistem Informasi Manajemen Inventaris" required>
                            </div>
                            <div class="editor-field">
                                <label>Peran Anda</label>
                                <input type="text" name="projects[{{ $idx }}][role]" class="form-control" value="{{ $proj['role'] ?? '' }}" placeholder="Contoh: Full-Stack Developer & Analyst">
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="editor-field">
                                <label>Waktu / Periode</label>
                                <input type="text" name="projects[{{ $idx }}][period]" class="form-control" value="{{ $proj['period'] ?? '' }}" placeholder="Contoh: 2024">
                            </div>
                            <div class="editor-field">
                                <label>Teknologi yang Digunakan</label>
                                <input type="text" name="projects[{{ $idx }}][technologies]" class="form-control" value="{{ $proj['technologies'] ?? '' }}" placeholder="Contoh: Laravel, MySQL, Bootstrap">
                            </div>
                            <div class="editor-field">
                                <label>Tautan / URL Proyek</label>
                                <input type="url" name="projects[{{ $idx }}][url]" class="form-control" value="{{ $proj['url'] ?? '' }}" placeholder="https://github.com/...">
                            </div>
                        </div>
                        <div class="editor-field" style="margin-bottom: 0;">
                            <label>Deskripsi Proyek</label>
                            <textarea name="projects[{{ $idx }}][description]" class="form-control" style="min-height: 75px;" placeholder="Jelaskan tujuan proyek, teknologi yang diimplementasikan, dan hasil yang diperoleh...">{{ $proj['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="btn-add-project" class="btn-add-row">
                <x-app.icon name="plus" />
                <span>Tambah Proyek</span>
            </button>
        </section>

        <!-- 7. SERTIFIKASI & LISENSI -->
        <section id="section-certifications" class="cv-card" style="margin-bottom: 24px;">
            <div class="cv-card-header">
                <h2 class="cv-card-title">
                    <x-app.icon name="award" />
                    <span>7. Sertifikasi &amp; Lisensi</span>
                </h2>
            </div>

            <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
                Tambahkan sertifikasi keahlian profesional, kursus terverifikasi, atau lisensi kompetensi yang pernah Anda raih.
            </p>

            <div id="certification-repeater" class="repeater-container" data-prefix="Sertifikasi">
                @php
                    $certifications = old('certifications', $cv->getCertificationsList());
                @endphp
                @foreach ($certifications as $idx => $cert)
                    <div class="repeater-item">
                        <div class="repeater-item-header">
                            <h3 class="repeater-item-title" data-label="Sertifikasi">Sertifikasi #{{ $loop->iteration }}</h3>
                            <button type="button" class="btn-remove-row" aria-label="Hapus sertifikasi ini">
                                <x-app.icon name="trash" />
                                <span>Hapus</span>
                            </button>
                        </div>
                        <div class="form-grid-2">
                            <div class="editor-field">
                                <label>Nama Sertifikasi <span class="required-indicator">*</span></label>
                                <input type="text" name="certifications[{{ $idx }}][name]" class="form-control" value="{{ $cert['name'] ?? '' }}" placeholder="Contoh: Google Data Analytics Professional Certificate" required>
                            </div>
                            <div class="editor-field">
                                <label>Organisasi / Lembaga Penerbit</label>
                                <input type="text" name="certifications[{{ $idx }}][issuer]" class="form-control" value="{{ $cert['issuer'] ?? '' }}" placeholder="Contoh: Coursera / Google">
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="editor-field">
                                <label>Tanggal Terbit</label>
                                <input type="text" name="certifications[{{ $idx }}][issue_date]" class="form-control" value="{{ $cert['issue_date'] ?? '' }}" placeholder="Contoh: 2024">
                            </div>
                            <div class="editor-field">
                                <label>ID Kredensial / Lisensi</label>
                                <input type="text" name="certifications[{{ $idx }}][credential_id]" class="form-control" value="{{ $cert['credential_id'] ?? '' }}" placeholder="Contoh: CERT-98234-XYZ">
                            </div>
                            <div class="editor-field">
                                <label>URL Verifikasi Kredensial</label>
                                <input type="url" name="certifications[{{ $idx }}][credential_url]" class="form-control" value="{{ $cert['credential_url'] ?? '' }}" placeholder="https://coursera.org/verify/...">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="btn-add-certification" class="btn-add-row">
                <x-app.icon name="plus" />
                <span>Tambah Sertifikasi</span>
            </button>
        </section>

        <!-- Sticky Bottom Actions -->
        <div class="sticky-bottom-actions">
            <div>
                <a href="{{ route('cv.index') }}" class="button button--outline">Batal & Kembali</a>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="button button--primary">
                    <x-app.icon name="check" />
                    <span>Simpan Perubahan CV</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
