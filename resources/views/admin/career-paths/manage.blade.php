@extends('layouts.app')
@section('title', 'Jalur Karier — ' . $profession->name . ' — Career Compass')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/career-paths.css') }}?v={{ filemtime(public_path('css/career-paths.css')) }}">
    <script src="{{ asset('js/career-paths.js') }}?v={{ filemtime(public_path('js/career-paths.js')) }}" defer></script>
@endpush

@section('content')
    <div class="career-path-page">
        <a class="back-link" href="{{ route('admin.professions.index') }}">
            <x-app.icon name="back" /> Kembali ke Manajemen Profesi
        </a>

        <div class="career-path-meta">
            <p class="profession-eyebrow" style="margin: 0;">MANAJEMEN DATA &bull; CAREER PATH</p>
            @if ($profession->careerCategory)
                <span class="profession-category-badge">{{ $profession->careerCategory->name }}</span>
            @endif
        </div>

        <div class="profession-heading">
            <div>
                <h1>Jalur Karier — {{ $profession->name }}</h1>
                <p>Kelola tahapan perkembangan karier dan hubungkan kompetensi yang relevan untuk setiap tahapan.</p>
            </div>
            <button class="button button--primary" type="button" data-open-create-modal>
                <x-app.icon name="plus" /> Tambah Tahapan
            </button>
        </div>

        @if (session('status'))
            <div class="profession-notice profession-notice--success" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="profession-notice profession-notice--error" role="alert">
                <strong>Periksa kembali isian berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($stages->isEmpty())
            <div class="career-path-empty">
                <x-app.icon name="compass" />
                <h3>Belum Ada Tahapan Karier</h3>
                <p>Profesi {{ $profession->name }} belum memiliki tahapan karier terstruktur. Mulai dengan menambahkan tahapan
                    awal seperti Junior atau Entry Level.</p>
                <button class="button button--primary" type="button" data-open-create-modal>
                    <x-app.icon name="plus" /> Tambah Tahapan Pertama
                </button>
            </div>
        @else
            <div class="career-path-timeline">
                @foreach ($stages as $stage)
                    <article class="stage-card" data-stage-card data-stage-id="{{ $stage->id }}">
                        <div class="stage-top">
                            <div class="stage-info">
                                <span class="stage-badge">Tahap {{ $loop->iteration }}</span>
                                <h2 class="stage-title">{{ $stage->title }}</h2>
                            </div>
                            <div class="stage-actions">
                                <div class="order-btn-group" aria-label="Ubah urutan tahapan">
                                    <button type="button" class="order-btn order-btn--up" data-order-action="up"
                                        data-stage-id="{{ $stage->id }}" title="Pindahkan ke atas" @if ($loop->first) disabled
                                        aria-disabled="true" @endif>
                                        <x-app.icon name="chevron" />
                                        <span class="sr-only">Pindahkan ke atas</span>
                                    </button>
                                    <button type="button" class="order-btn order-btn--down" data-order-action="down"
                                        data-stage-id="{{ $stage->id }}" title="Pindahkan ke bawah" @if ($loop->last) disabled
                                        aria-disabled="true" @endif>
                                        <x-app.icon name="chevron" />
                                        <span class="sr-only">Pindahkan ke bawah</span>
                                    </button>
                                </div>
                                <button type="button" class="button button--small button--outline" data-edit-stage
                                    data-stage-id="{{ $stage->id }}" data-stage-title="{{ $stage->title }}"
                                    data-stage-desc="{{ $stage->description }}"
                                    data-stage-competencies="{{ json_encode($stage->competencies->pluck('id')) }}">
                                    Edit
                                </button>
                                <button type="button" class="button button--small button--outline text-danger" data-delete-stage
                                    data-stage-id="{{ $stage->id }}" data-stage-title="{{ $stage->title }}">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <p class="stage-description">{{ $stage->description }}</p>

                        <div class="stage-competencies">
                            <span class="stage-competencies-label">Kompetensi yang Dibutuhkan:</span>
                            <div class="competency-chips">
                                @forelse ($stage->competencies as $competency)
                                    <span class="competency-chip">
                                        <x-app.icon name="check" /> {{ $competency->name }}
                                    </span>
                                @empty
                                    <span class="competency-empty">Belum ada kompetensi yang dihubungkan.</span>
                                @endforelse
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Form tersembunyi untuk penataan ulang urutan tahapan (Reorder) --}}
            <form method="POST" action="{{ route('admin.professions.career-paths.reorder', $profession) }}" data-reorder-form
                hidden>
                @csrf
                @method('PUT')
            </form>
        @endif
    </div>

    {{-- Modal Dialog Tambah Tahapan (AC-01 & AC-02) --}}
    <dialog class="stage-dialog" data-create-dialog aria-labelledby="create-dialog-title">
        <div class="dialog-header">
            <h2 id="create-dialog-title">Tambah Tahapan Karier</h2>
            <button type="button" class="dialog-close" data-close-dialog aria-label="Tutup modal">
                <x-app.icon name="close" />
            </button>
        </div>
        <form method="POST" action="{{ route('admin.professions.career-paths.store', $profession) }}">
            @csrf
            <div class="dialog-body">
                <div class="editor-field">
                    <label for="create-title">Nama Tahapan / Jabatan <span aria-hidden="true">*</span></label>
                    <input class="form-control" id="create-title" name="title" type="text"
                        placeholder="Contoh: Junior System Analyst" maxlength="150" required>
                </div>

                <div class="editor-field">
                    <label for="create-description">Deskripsi Tahapan <span aria-hidden="true">*</span></label>
                    <textarea class="form-control" id="create-description" name="description" rows="3" maxlength="5000"
                        placeholder="Jelaskan peran, tanggung jawab, dan kualifikasi pada tahapan ini." required></textarea>
                </div>

                <div class="editor-field" style="margin-bottom: 0;">
                    <label>Hubungkan Kompetensi yang Dibutuhkan</label>
                    <p class="field-help" style="margin-top: -4px; margin-bottom: 8px;">Pilih satu atau lebih kompetensi
                        yang diperlukan untuk mencapai tahapan ini.</p>
                    <div class="competency-picker">
                        <div class="competency-search-wrap">
                            <x-app.icon name="search" />
                            <input type="search" class="competency-search-input" placeholder="Cari kompetensi..."
                                data-competency-search aria-label="Cari kompetensi">
                        </div>
                        <div class="competency-list">
                            @foreach ($competencies as $competency)
                                <label class="competency-option">
                                    <input type="checkbox" name="competency_ids[]" value="{{ $competency->id }}">
                                    <div class="competency-option-text">
                                        <span class="competency-option-name">{{ $competency->name }}</span>
                                        @if ($competency->description)
                                            <span
                                                class="competency-option-desc">{{ Str::limit($competency->description, 80) }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="competency-picker-empty" hidden>Kompetensi tidak ditemukan.</div>
                    </div>
                </div>
            </div>
            <div class="dialog-footer">
                <button type="button" class="button button--outline" data-close-dialog>Batal</button>
                <button type="submit" class="button button--primary">Simpan Tahapan</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Dialog Edit Tahapan (AC-04) --}}
    <dialog class="stage-dialog" data-edit-dialog aria-labelledby="edit-dialog-title">
        <div class="dialog-header">
            <h2 id="edit-dialog-title">Edit Tahapan Karier</h2>
            <button type="button" class="dialog-close" data-close-dialog aria-label="Tutup modal">
                <x-app.icon name="close" />
            </button>
        </div>
        <form method="POST" action=""
            data-base-action="{{ url('admin/professions/' . $profession->id . '/career-paths') }}">
            @csrf
            @method('PUT')
            <div class="dialog-body">
                <div class="editor-field">
                    <label for="edit-title">Nama Tahapan / Jabatan <span aria-hidden="true">*</span></label>
                    <input class="form-control" id="edit-title" name="title" type="text" maxlength="150" required>
                </div>

                <div class="editor-field">
                    <label for="edit-description">Deskripsi Tahapan <span aria-hidden="true">*</span></label>
                    <textarea class="form-control" id="edit-description" name="description" rows="3" maxlength="5000"
                        required></textarea>
                </div>

                <div class="editor-field" style="margin-bottom: 0;">
                    <label>Hubungkan Kompetensi yang Dibutuhkan</label>
                    <p class="field-help" style="margin-top: -4px; margin-bottom: 8px;">Pilih satu atau lebih kompetensi
                        yang diperlukan untuk mencapai tahapan ini.</p>
                    <div class="competency-picker">
                        <div class="competency-search-wrap">
                            <x-app.icon name="search" />
                            <input type="search" class="competency-search-input" placeholder="Cari kompetensi..."
                                data-competency-search aria-label="Cari kompetensi">
                        </div>
                        <div class="competency-list">
                            @foreach ($competencies as $competency)
                                <label class="competency-option">
                                    <input type="checkbox" name="competency_ids[]" value="{{ $competency->id }}">
                                    <div class="competency-option-text">
                                        <span class="competency-option-name">{{ $competency->name }}</span>
                                        @if ($competency->description)
                                            <span
                                                class="competency-option-desc">{{ Str::limit($competency->description, 80) }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="competency-picker-empty" hidden>Kompetensi tidak ditemukan.</div>
                    </div>
                </div>
            </div>
            <div class="dialog-footer">
                <button type="button" class="button button--outline" data-close-dialog>Batal</button>
                <button type="submit" class="button button--primary">Simpan Perubahan</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Dialog Konfirmasi Hapus (AC-05) --}}
    <dialog class="confirm-dialog" data-delete-dialog aria-labelledby="delete-dialog-title">
        <h2 id="delete-dialog-title" style="margin-top: 0;">Hapus Tahapan Karier?</h2>
        <p data-delete-copy style="font-weight: 500;"></p>
        <p style="color: var(--muted); font-size: 12px; line-height: 1.6;">Tahapan ini beserta pemetaan kompetensinya akan
            dihapus dari jalur karier. Data master profesi dan kompetensi tetap aman tersimpan.</p>
        <form method="POST" action=""
            data-base-action="{{ url('admin/professions/' . $profession->id . '/career-paths') }}">
            @csrf
            @method('DELETE')
            <div class="dialog-actions" style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="button button--outline" data-close-dialog>Batal</button>
                <button type="submit" class="button button--primary text-danger"
                    style="background: #a13930; border-color: #a13930; color: white !important;">Hapus Tahapan</button>
            </div>
        </form>
    </dialog>
@endsection