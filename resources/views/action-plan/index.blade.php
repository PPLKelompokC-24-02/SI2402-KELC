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
            <p>Susun dan kelola rencana kegiatan pengembangan kompetensi beserta tenggat waktu dan status pelaksanaan untuk mencapai tujuan karier Anda.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="template-notice template-notice--success" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="template-notice template-notice--error" role="alert">
            <strong style="display: block; margin-bottom: 4px;">Terdapat kesalahan pada input formulir:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Ringkasan Statistik Progres Rencana Aksi -->
    <div class="stats-summary">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Aktivitas</span>
                <x-app.icon name="plan" class="stat-icon" />
            </div>
            <div class="stat-value">{{ $summary['total'] }}</div>
            <div class="stat-footer">Aktivitas terdaftar</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Belum Dimulai</span>
                <x-app.icon name="calendar" class="stat-icon" />
            </div>
            <div class="stat-value" style="color: #63756c;">{{ $summary['not_started'] }}</div>
            <div class="stat-footer">Menunggu pelaksanaan</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Sedang Berjalan</span>
                <x-app.icon name="progress" class="stat-icon" />
            </div>
            <div class="stat-value" style="color: #b58017;">{{ $summary['in_progress'] }}</div>
            <div class="stat-footer">Dalam proses pengerjaan</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Selesai</span>
                <x-app.icon name="check" class="stat-icon" />
            </div>
            <div class="stat-value" style="color: #246d46;">{{ $summary['completed'] }}</div>
            <div class="stat-footer">Berhasil diselesaikan</div>
        </div>
    </div>

    <!-- Kartu Persentase Ketercapaian -->
    <div class="overall-progress-card">
        <div class="progress-meta">
            <div>
                <h3>Tingkat Penyelesaian Rencana Aksi</h3>
                <div class="progress-caption">
                    @if ($summary['total'] > 0)
                        {{ $summary['completed'] }} dari {{ $summary['total'] }} aktivitas telah diselesaikan.
                    @else
                        Belum ada aktivitas yang ditambahkan.
                    @endif
                </div>
            </div>
            <div class="progress-percentage">{{ $summary['percentage'] }}%</div>
        </div>
        <div class="progress-track" role="progressbar" aria-valuenow="{{ $summary['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-fill" style="width: {{ $summary['percentage'] }}%;"></div>
        </div>
    </div>

    <!-- Formulir Tambah Aktivitas Mandiri -->
    <details class="collapsible-form-card" {{ $errors->any() ? 'open' : '' }}>
        <summary class="collapsible-summary">
            <div class="collapsible-summary-content">
                <x-app.icon name="plus" />
                <span>Tambah Aktivitas Pengembangan Diri Mandiri</span>
            </div>
            <span class="collapsible-summary-badge">Buka / Tutup Formulir &darr;</span>
        </summary>
        <div class="collapsible-content">
            <form method="POST" action="{{ route('action-plan.store') }}" style="margin-top: 20px;">
                @csrf
                <div class="editor-field">
                    <label for="create_title">Judul Aktivitas <span class="required-indicator">*</span></label>
                    <input id="create_title" name="title" type="text" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Menyelesaikan Kursus Data Science dengan Python" required maxlength="255" aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}">
                    @error('title')
                        <p class="editor-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="editor-field">
                    <label for="create_description">Deskripsi Aktivitas</label>
                    <textarea id="create_description" name="description" class="form-control" rows="3" placeholder="Tuliskan target, langkah konkret, atau catatan pendukung kegiatan...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="editor-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div class="editor-field">
                        <label for="create_due_date">Tenggat Waktu (Target Selesai)</label>
                        <input id="create_due_date" name="due_date" type="date" class="form-control" value="{{ old('due_date') }}" aria-invalid="{{ $errors->has('due_date') ? 'true' : 'false' }}">
                        <p class="field-help">Pilih batas target penyelesaian kegiatan pengembangan diri Anda.</p>
                        @error('due_date')
                            <p class="editor-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="editor-field">
                        <label for="create_status">Status Pelaksanaan <span class="required-indicator">*</span></label>
                        <select id="create_status" name="status" class="form-control" required>
                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption }}" {{ old('status', 'Not Started') === $statusOption ? 'selected' : '' }}>
                                    {{ $statusOption }}
                                </option>
                            @endforeach
                        </select>
                        <p class="field-help">Status awal saat aktivitas ini baru dibuat.</p>
                        @error('status')
                            <p class="editor-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="margin-top: 12px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="button button--primary">
                        <x-app.icon name="plus" /> Simpan Aktivitas
                    </button>
                </div>
            </form>
        </div>
    </details>

    <!-- Bagian Aktivitas Pribadi Mahasiswa (US-17) -->
    <section class="student-section" aria-labelledby="plans-heading">
        <div class="student-section-heading">
            <h2 id="plans-heading">Aktivitas Career Action Plan Saya</h2>
            <p>Kelola jadwal, tenggat waktu, dan pantau status pelaksanaan kegiatan persiapan karier Anda secara terstruktur.</p>
        </div>

        <div class="template-list-card">
            <!-- Toolbar Filter Status dan Pencarian -->
            <div class="plan-toolbar">
                <div class="filter-tabs" role="tablist" aria-label="Filter status aktivitas">
                    <a href="{{ route('action-plan.index', array_filter(['q' => $searchQuery])) }}"
                       class="filter-tab {{ $currentStatus === 'all' ? 'is-active' : '' }}">
                        Semua <span class="filter-badge">{{ $summary['total'] }}</span>
                    </a>
                    <a href="{{ route('action-plan.index', array_filter(['status' => 'Not Started', 'q' => $searchQuery])) }}"
                       class="filter-tab {{ $currentStatus === 'Not Started' ? 'is-active' : '' }}">
                        Belum Dimulai <span class="filter-badge">{{ $summary['not_started'] }}</span>
                    </a>
                    <a href="{{ route('action-plan.index', array_filter(['status' => 'In Progress', 'q' => $searchQuery])) }}"
                       class="filter-tab {{ $currentStatus === 'In Progress' ? 'is-active' : '' }}">
                        Sedang Berjalan <span class="filter-badge">{{ $summary['in_progress'] }}</span>
                    </a>
                    <a href="{{ route('action-plan.index', array_filter(['status' => 'Completed', 'q' => $searchQuery])) }}"
                       class="filter-tab {{ $currentStatus === 'Completed' ? 'is-active' : '' }}">
                        Selesai <span class="filter-badge">{{ $summary['completed'] }}</span>
                    </a>
                </div>

                <form method="GET" action="{{ route('action-plan.index') }}" class="search-field" style="margin: 0;">
                    @if ($currentStatus !== 'all')
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif
                    <x-app.icon name="search" />
                    <input type="search" name="q" value="{{ $searchQuery }}" class="form-control" placeholder="Cari aktivitas..." aria-label="Cari aktivitas">
                </form>
            </div>

            @if ($actionPlans->isEmpty())
                <div class="template-empty">
                    <x-app.icon name="plan" />
                    @if ($searchQuery !== '' || $currentStatus !== 'all')
                        <h2>Tidak ada aktivitas yang sesuai</h2>
                        <p>Tidak ditemukan aktivitas dengan kriteria filter atau pencarian saat ini. Coba reset filter Anda.</p>
                        <div style="margin-top: 14px;">
                            <a href="{{ route('action-plan.index') }}" class="button button--outline button--small">
                                Reset Filter
                            </a>
                        </div>
                    @else
                        <h2>Belum ada aktivitas di Career Action Plan</h2>
                        <p>Tambahkan aktivitas pengembangan diri Anda menggunakan formulir di atas, atau pilih dari referensi template aktivitas di bawah.</p>
                    @endif
                </div>
            @else
                <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel aktivitas Career Action Plan saya">
                    <table class="action-plan-table">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 240px;">Aktivitas</th>
                                <th scope="col" style="min-width: 220px;">Deskripsi</th>
                                <th scope="col" style="width: 150px;">Tenggat Waktu</th>
                                <th scope="col" style="width: 170px;">Status Pelaksanaan</th>
                                <th scope="col" style="width: 150px;">Sumber Referensi</th>
                                <th scope="col" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actionPlans as $plan)
                                <tr>
                                    <td>
                                        <strong style="color: var(--green); font-size: 13px; display: block; margin-bottom: 3px;">
                                            {{ $plan->title }}
                                        </strong>
                                        <span style="font-size: 10px; color: var(--muted);">
                                            Ditambahkan {{ $plan->created_at ? $plan->created_at->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td style="color: #52645c; font-size: 11px; line-height: 1.6;">
                                        {{ $plan->description ?: 'Tidak ada deskripsi.' }}
                                    </td>
                                    <td>
                                        @if ($plan->due_date)
                                            <div class="due-date-pill {{ $plan->isOverdue() ? 'due-date-pill--overdue' : '' }}" title="{{ $plan->isOverdue() ? 'Melewati batas tenggat waktu!' : 'Batas penyelesaian' }}">
                                                <x-app.icon name="calendar" />
                                                <span>{{ $plan->due_date->format('d M Y') }}</span>
                                            </div>
                                            @if ($plan->isOverdue())
                                                <div style="font-size: 10px; color: #b52f23; font-weight: 600; margin-top: 3px;">
                                                    Terlewat
                                                </div>
                                            @endif
                                        @else
                                            <span class="due-date-pill due-date-pill--empty">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <span class="status-pill {{ $plan->statusBadgeClass() }}">
                                                {{ $plan->status }}
                                            </span>
                                        </div>
                                        <!-- Quick Status Changer Form -->
                                        <form method="POST" action="{{ route('action-plan.status.update', $plan) }}" class="quick-status-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="quick-status-select" onchange="this.form.submit()" aria-label="Ubah status aktivitas {{ $plan->title }}">
                                                @foreach ($statuses as $opt)
                                                    <option value="{{ $opt }}" {{ $plan->status === $opt ? 'selected' : '' }}>
                                                        {{ $opt }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td style="font-size: 11px; color: var(--muted);">
                                        @if ($plan->template)
                                            <span title="Dibuat dari master template: {{ $plan->template->title }}">
                                                Template: <strong>{{ $plan->template->title }}</strong>
                                            </span>
                                        @else
                                            <em>Mandiri</em>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <a href="{{ route('action-plan.edit', $plan) }}" class="text-link" style="color: var(--navy); font-weight: 600;">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('action-plan.destroy', $plan) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-link text-link--muted" type="submit" onclick="return confirm('Hapus aktivitas ini dari rencana aksi Anda?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    <!-- Bagian Referensi Template Master dari Admin (US-16 Integration Point) -->
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
</div>
@endsection
