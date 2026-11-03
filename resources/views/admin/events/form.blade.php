@extends('layouts.app')
@section('title', ($event->exists ? 'Edit Career Event' : 'Tambah Career Event') . ' — Career Compass')

@push('head')
<link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
<style>
    .editor-section { padding: 26px; border: 1px solid var(--border); border-radius: 9px; background: white; margin-bottom: 22px; }
    .form-actions-bar { display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border); }
    .poster-preview-box { width: 180px; height: 240px; border: 1px dashed #d7dfd6; border-radius: 6px; background: #f5f7f2; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 12px; }
    .poster-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .poster-preview-placeholder { text-align: center; color: var(--muted); font-size: 11px; padding: 12px; }
    .poster-preview-placeholder svg { width: 32px; height: 32px; margin-bottom: 6px; opacity: 0.6; }
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: white;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23566a5c' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 14px 14px;
        padding-right: 38px;
        padding-left: 12px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="profession-page">
    <a class="back-link" href="{{ route('admin.events.index') }}">
        <x-app.icon name="back" /> Kembali ke Manajemen Event
    </a>

    <div class="profession-heading">
        <div>
            <p class="profession-eyebrow">FORMULIR CAREER EVENT</p>
            <h1>{{ $event->exists ? 'Edit Career Event' : 'Tambah Career Event Baru' }}</h1>
            <p>{{ $event->exists ? 'Perbarui informasi kegiatan pengembangan karier mahasiswa.' : 'Lengkapi detail kegiatan, jadwal, poster, dan tautan pendaftaran resmi.' }}</p>
        </div>
        @if ($event->exists)
            <div class="profession-status {{ $event->isPublished() ? 'profession-status--published' : 'profession-status--draft' }}">
                <span></span> {{ $event->isPublished() ? 'Terbit' : 'Draf' }}
            </div>
        @endif
    </div>

    @if (isset($errors) && $errors->any())
        <div class="profession-notice profession-notice--error" role="alert" style="margin-bottom: 20px;">
            <p><strong>Terdapat kesalahan pada isian formulir:</strong></p>
            <ul style="margin: 8px 0 0 18px; font-size: 11px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" 
          action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" 
          enctype="multipart/form-data">
        @csrf
        @if ($event->exists)
            @method('PUT')
        @endif

        {{-- Seksi 1: Informasi Dasar --}}
        <section class="editor-section">
            <div class="section-heading">
                <h2><span>01</span> Informasi Dasar Kegiatan</h2>
                <p>Masukkan judul, kategori, penyelenggara, dan deskripsi detail event.</p>
            </div>

            <div class="editor-field">
                <label for="title">Judul Event <span>*</span></label>
                <input class="form-control @error('title') is-invalid @enderror" 
                       id="title" 
                       type="text" 
                       name="title" 
                       value="{{ old('title', $event->title) }}" 
                       placeholder="Contoh: Telkom Career Fair 2026 / Workshop UI/UX Design" 
                       maxlength="200" 
                       required>
                @error('title') <p class="editor-field-error">{{ $message }}</p> @enderror
            </div>

            <div class="editor-grid editor-grid--two">
                <div class="editor-field">
                    <label for="category">Kategori Event <span>*</span></label>
                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="">Pilih kategori kegiatan</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $event->category) === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="editor-field-error">{{ $message }}</p> @enderror
                </div>

                <div class="editor-field">
                    <label for="organizer">Penyelenggara <span>*</span></label>
                    <input class="form-control @error('organizer') is-invalid @enderror" 
                           id="organizer" 
                           type="text" 
                           name="organizer" 
                           value="{{ old('organizer', $event->organizer) }}" 
                           placeholder="Contoh: CDC Telkom University / Google Developer Student Club" 
                           maxlength="150" 
                           required>
                    @error('organizer') <p class="editor-field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="editor-field">
                <label for="description">Deskripsi Lengkap Kegiatan <span>*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="6" 
                          placeholder="Jelaskan mengenai agenda, pembicara, manfaat kegiatan, dan persyaratan peserta…" 
                          required>{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="editor-field-error">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Seksi 2: Jadwal & Lokasi --}}
        <section class="editor-section">
            <div class="section-heading">
                <h2><span>02</span> Jadwal Waktu & Lokasi</h2>
                <p>Tentukan tanggal dan waktu pelaksanaan serta lokasi kegiatan.</p>
            </div>

            <div class="editor-grid editor-grid--two">
                <div class="editor-field">
                    <label for="start_at">Tanggal & Waktu Mulai <span>*</span></label>
                    <input class="form-control @error('start_at') is-invalid @enderror" 
                           id="start_at" 
                           type="datetime-local" 
                           name="start_at" 
                           value="{{ old('start_at', $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : '') }}" 
                           required>
                    <p class="field-help">Format: Tanggal dan jam pelaksanaan kegiatan (WIB).</p>
                    @error('start_at') <p class="editor-field-error">{{ $message }}</p> @enderror
                </div>

                <div class="editor-field">
                    <label for="end_at">Tanggal & Waktu Selesai <span class="optional-label" style="color: var(--muted); font-weight: normal;">(Opsional)</span></label>
                    <input class="form-control @error('end_at') is-invalid @enderror" 
                           id="end_at" 
                           type="datetime-local" 
                           name="end_at" 
                           value="{{ old('end_at', $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : '') }}">
                    <p class="field-help">Dapat dikosongkan jika kegiatan berlangsung hingga selesai.</p>
                    @error('end_at') <p class="editor-field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="editor-field">
                <label for="location">Lokasi Kegiatan <span>*</span></label>
                <input class="form-control @error('location') is-invalid @enderror" 
                       id="location" 
                       type="text" 
                       name="location" 
                       value="{{ old('location', $event->location) }}" 
                       placeholder="Contoh: Auditorium Gedung Damar, Kampus Jakarta / Zoom Meeting Online" 
                       maxlength="255" 
                       required>
                @error('location') <p class="editor-field-error">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Seksi 3: Poster & Tautan Pendaftaran Resmi --}}
        <section class="editor-section">
            <div class="section-heading">
                <h2><span>03</span> Poster & Tautan Pendaftaran Resmi</h2>
                <p>Unggah poster kegiatan dan sediakan tautan pendaftaran eksternal resmi bagi mahasiswa.</p>
            </div>

            <div class="editor-field">
                <label for="poster">Poster Event</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    <div class="poster-preview-box" id="poster-box" style="position: relative;">
                        @if ($event->poster_path)
                            <img id="poster-preview-img" src="{{ route('admin.events.poster', $event) }}?v={{ $event->updated_at?->timestamp }}" alt="Poster {{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="poster-preview-placeholder" id="poster-placeholder" style="display: none;">
                                <x-app.icon name="calendar" />
                                <p id="poster-placeholder-text">Belum ada poster</p>
                            </div>
                        @else
                            <img id="poster-preview-img" src="" alt="Pratinjau Poster" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                            <div class="poster-preview-placeholder" id="poster-placeholder">
                                <x-app.icon name="calendar" />
                                <p id="poster-placeholder-text">Belum ada poster</p>
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 240px;">
                        <input class="form-control @error('poster') is-invalid @enderror" 
                               id="poster" 
                               type="file" 
                               name="poster" 
                               accept="image/png,image/jpeg,image/webp">
                        <p class="field-help">Format yang didukung: JPG, PNG, WEBP. Maksimal ukuran berkas: 2 MB.</p>
                        <p id="poster-filename-status" style="font-size: 11px; color: var(--navy); margin-top: 6px; font-weight: 500; display: none;"></p>
                        @if ($event->poster_path)
                            <label class="remove-image" style="margin-top: 10px;">
                                <input type="checkbox" name="remove_poster" value="1"> Hapus poster saat ini
                            </label>
                        @endif
                        @error('poster') <p class="editor-field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="editor-field">
                <label for="registration_url">Tautan Pendaftaran Resmi <span>*</span></label>
                <input class="form-control @error('registration_url') is-invalid @enderror" 
                       id="registration_url" 
                       type="url" 
                       name="registration_url" 
                       value="{{ old('registration_url', $event->registration_url) }}" 
                       placeholder="https://bit.ly/pendaftaran-event atau https://event.telkomuniversity.ac.id/..." 
                       maxlength="500" 
                       required>
                <p class="field-help">PENTING: Tautan harus diawali dengan http:// atau https:// menuju halaman pendaftaran eksternal resmi.</p>
                @error('registration_url') <p class="editor-field-error">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Bar Tombol Aksi --}}
        <div class="form-actions-bar">
            <a class="button button--outline" href="{{ route('admin.events.index') }}">Batal</a>
            <button class="button button--outline" type="submit" name="action" value="draft">
                Simpan sebagai Draf
            </button>
            <button class="button button--primary" type="submit" name="action" value="publish">
                {{ $event->exists ? 'Perbarui & Terbitkan' : 'Terbitkan Event' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Poster preview logic ────────────────────────────────────────────────
    const posterInput    = document.getElementById('poster');
    const previewImg     = document.getElementById('poster-preview-img');
    const placeholder    = document.getElementById('poster-placeholder');
    const placeholderText= document.getElementById('poster-placeholder-text');
    const filenameStatus = document.getElementById('poster-filename-status');
    const removeCheckbox = document.querySelector('input[name="remove_poster"]');
    const initialSrc     = previewImg && previewImg.getAttribute('src') ? previewImg.getAttribute('src') : null;
    let objectUrl = null;

    if (posterInput) {
        posterInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Harap pilih gambar dengan format JPG, PNG, atau WEBP.');
                posterInput.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran berkas poster melebihi batas maksimal 2 MB.');
                posterInput.value = '';
                return;
            }

            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);

            if (previewImg)     { previewImg.src = objectUrl; previewImg.style.display = 'block'; }
            if (placeholder)      placeholder.style.display = 'none';
            if (filenameStatus) {
                filenameStatus.textContent = '✓ Pratinjau poster terpilih: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                filenameStatus.style.display = 'block';
            }
            if (removeCheckbox) removeCheckbox.checked = false;
        });
    }

    if (removeCheckbox) {
        removeCheckbox.addEventListener('change', function () {
            if (this.checked) {
                if (posterInput) posterInput.value = '';
                if (objectUrl)   { URL.revokeObjectURL(objectUrl); objectUrl = null; }
                if (previewImg)  previewImg.style.display = 'none';
                if (placeholder) { placeholder.style.display = 'block'; if (placeholderText) placeholderText.textContent = 'Poster akan dihapus saat disimpan'; }
                if (filenameStatus) filenameStatus.style.display = 'none';
            } else {
                if (initialSrc) {
                    if (previewImg)  { previewImg.src = initialSrc; previewImg.style.display = 'block'; }
                    if (placeholder)   placeholder.style.display = 'none';
                } else {
                    if (previewImg)  previewImg.style.display = 'none';
                    if (placeholder) { placeholder.style.display = 'block'; if (placeholderText) placeholderText.textContent = 'Belum ada poster'; }
                }
            }
        });
    }

    // ─── Client-side date validation (AC-05) ────────────────────────────────
    // Mencegah form dikirim ke server saat tanggal tidak valid,
    // sehingga halaman TIDAK di-reload dan file poster TETAP ada.
    const form        = document.querySelector('form');
    const startInput  = document.getElementById('start_at');
    const endInput    = document.getElementById('end_at');

    // Helper: tampilkan atau hapus pesan error di bawah field
    function setFieldError(input, message) {
        // cari atau buat elemen error di bawah field
        let errEl = input.parentElement.querySelector('.js-date-error');
        if (message) {
            if (!errEl) {
                errEl = document.createElement('p');
                errEl.className = 'editor-field-error js-date-error';
                input.parentElement.appendChild(errEl);
            }
            errEl.textContent = message;
            input.classList.add('is-invalid');
        } else {
            if (errEl) errEl.remove();
            input.classList.remove('is-invalid');
        }
    }

    // Validasi setiap kali nilai berubah (umpan balik langsung)
    function validateDates() {
        let valid = true;

        // Tanggal mulai wajib diisi
        if (startInput && !startInput.value) {
            setFieldError(startInput, 'Tanggal dan waktu mulai event wajib diisi.');
            valid = false;
        } else if (startInput) {
            setFieldError(startInput, '');
        }

        // Tanggal selesai: jika diisi harus ≥ tanggal mulai
        if (endInput && endInput.value && startInput && startInput.value) {
            const start = new Date(startInput.value);
            const end   = new Date(endInput.value);
            if (end < start) {
                setFieldError(endInput, 'Waktu selesai harus setelah atau sama dengan waktu mulai.');
                valid = false;
            } else {
                setFieldError(endInput, '');
            }
        } else if (endInput) {
            setFieldError(endInput, '');
        }

        return valid;
    }

    if (startInput) startInput.addEventListener('change', validateDates);
    if (endInput)   endInput.addEventListener('change', validateDates);

    // Cegat submit: jalankan validasi tanggal sebelum POST ke server
    if (form) {
        form.addEventListener('submit', function (e) {
            const datesOk = validateDates();
            if (!datesOk) {
                e.preventDefault();   // tahan pengiriman → halaman tidak reload → poster tetap ada
                // Scroll ke field yang salah
                const firstError = form.querySelector('.is-invalid');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});
</script>
@endsection
