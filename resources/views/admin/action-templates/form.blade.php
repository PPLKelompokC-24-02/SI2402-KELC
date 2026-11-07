@extends('layouts.app')
@section('title', ($template->exists ? 'Edit' : 'Tambah') . ' Template Aktivitas — Career Compass')
@push('head')
<link rel="stylesheet" href="{{ asset('css/action-templates.css') }}?v={{ filemtime(public_path('css/action-templates.css')) }}">
@endpush
@section('content')
<div class="template-page">
    <a class="back-link" href="{{ route('admin.action-templates.index') }}">
        <x-app.icon name="back" /> Kembali ke daftar template
    </a>

    <div class="template-heading">
        <div>
            <h1>{{ $template->exists ? 'Edit Template Aktivitas' : 'Tambah Template Aktivitas' }}</h1>
            <p>{{ $template->exists ? 'Perbarui informasi template master aktivitas pengembangan diri.' : 'Buat master template aktivitas baru yang dapat dijadikan referensi kegiatan Career Action Plan oleh mahasiswa.' }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="template-notice template-notice--error" role="alert">
            <strong>Terdapat kesalahan pada isian form:</strong>
            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $template->exists ? route('admin.action-templates.update', $template) : route('admin.action-templates.store') }}">
        @csrf
        @if ($template->exists)
            @method('PUT')
        @endif

        <div class="editor-section">
            <div class="section-heading">
                <h2>Informasi Template</h2>
                <p>Pastikan judul dan deskripsi jelas agar mahasiswa memahami tujuan aktivitas pengembangan kompetensi.</p>
            </div>

            <div class="editor-field">
                <label for="title">Judul Template <span class="required-indicator">*</span></label>
                <input class="form-control" id="title" type="text" name="title"
                       value="{{ old('title', $template->title) }}"
                       placeholder="Contoh: Mengikuti Kursus Online Pemrograman"
                       maxlength="255"
                       required
                       @error('title') aria-invalid="true" aria-describedby="title-error" @enderror>
                <p class="field-help">Nama atau judul kegiatan pengembangan kompetensi (maksimal 255 karakter).</p>
                @error('title')
                    <p class="editor-field-error" id="title-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="editor-field">
                <label for="description">Deskripsi Template <span class="required-indicator">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="6"
                          placeholder="Jelaskan gambaran umum kegiatan, tujuan pembelajaran, dan hasil yang diharapkan..."
                          maxlength="5000"
                          required
                          @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description', $template->description) }}</textarea>
                <p class="field-help">Panduan atau rincian kegiatan untuk membantu mahasiswa menyusun rencana aksi mereka.</p>
                @error('description')
                    <p class="editor-field-error" id="description-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="editor-field" style="margin-top: 24px; margin-bottom: 8px;">
                <input type="hidden" name="is_active" value="0">
                <label class="toggle-group" for="is_active">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           @checked(old('is_active', $template->exists ? $template->is_active : true))>
                    <span class="toggle-label">Status Aktif (Tersedia sebagai referensi bagi mahasiswa)</span>
                </label>
                <p class="field-help" style="margin-left: 28px;">Jika tidak dicentang, template akan berstatus nonaktif dan tidak ditampilkan kepada mahasiswa.</p>
                @error('is_active')
                    <p class="editor-field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="editor-actions">
            <div>
                <a class="button button--outline" href="{{ route('admin.action-templates.index') }}">Batal</a>
            </div>
            <div class="editor-action-buttons">
                <button class="button button--primary" type="submit">
                    {{ $template->exists ? 'Simpan Perubahan' : 'Tambah Template' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

