@extends('layouts.app')
@section('title', 'Edit Aktivitas — Career Action Plan')
@push('head')
<link rel="stylesheet" href="{{ asset('css/action-templates.css') }}?v={{ filemtime(public_path('css/action-templates.css')) }}">
@endpush
@section('content')
<div class="template-page">
    <a href="{{ route('action-plan.index') }}" class="back-link">
        <x-app.icon name="back" />
        <span>Kembali ke Career Action Plan</span>
    </a>

    <div class="template-heading">
        <div>
            <h1>Edit Aktivitas Action Plan</h1>
            <p>Perbarui rincian kegiatan, tenggat waktu, atau status pelaksanaan rencana pengembangan karier Anda.</p>
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

    <div class="editor-section">
        <div class="section-heading">
            <h2>Informasi Aktivitas Pengembangan Diri</h2>
            <p>Pastikan data rencana kegiatan dan tenggat waktu terisi dengan jelas agar target karier Anda terpantau baik.</p>
        </div>

        @if ($actionPlan->template)
            <div class="info-box">
                <x-app.icon name="plan" />
                <div>
                    <strong>Aktivitas Bersumber dari Template:</strong> {{ $actionPlan->template->title }}
                    <div style="font-size: 11px; margin-top: 4px; opacity: .85;">
                        Aktivitas ini berdiri sendiri sebagai rencana aksi mandiri Anda. Perubahan di sini tidak memengaruhi master template.
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('action-plan.update', $actionPlan) }}">
            @csrf
            @method('PUT')

            <div class="editor-field">
                <label for="title">Judul Aktivitas <span class="required-indicator">*</span></label>
                <input id="title" name="title" type="text" class="form-control" value="{{ old('title', $actionPlan->title) }}" required maxlength="255" aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}">
                @error('title')
                    <p class="editor-field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="editor-field">
                <label for="description">Deskripsi Aktivitas</label>
                <textarea id="description" name="description" class="form-control" rows="5" placeholder="Tuliskan target, langkah konkret, atau catatan kegiatan...">{{ old('description', $actionPlan->description) }}</textarea>
                <p class="field-help">Deskripsi detail target atau langkah-langkah pelaksanaan kegiatan.</p>
                @error('description')
                    <p class="editor-field-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                <div class="editor-field">
                    <label for="due_date">Tenggat Waktu (Target Selesai)</label>
                    <input id="due_date" name="due_date" type="date" class="form-control" value="{{ old('due_date', $actionPlan->due_date ? $actionPlan->due_date->format('Y-m-d') : '') }}" aria-invalid="{{ $errors->has('due_date') ? 'true' : 'false' }}">
                    <p class="field-help">Batas tanggal target penyelesaian aktivitas ini.</p>
                    @error('due_date')
                        <p class="editor-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="editor-field">
                    <label for="status">Status Pelaksanaan <span class="required-indicator">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        @foreach ($statuses as $statusOption)
                            <option value="{{ $statusOption }}" {{ old('status', $actionPlan->status) === $statusOption ? 'selected' : '' }}>
                                {{ $statusOption }}
                            </option>
                        @endforeach
                    </select>
                    <p class="field-help">Pilih status progres saat ini ('Not Started', 'In Progress', atau 'Completed').</p>
                    @error('status')
                        <p class="editor-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="editor-actions">
                <a href="{{ route('action-plan.index') }}" class="button button--outline">
                    Batal
                </a>
                <button type="submit" class="button button--primary">
                    <x-app.icon name="check" /> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

