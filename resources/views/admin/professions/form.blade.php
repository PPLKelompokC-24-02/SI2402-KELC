@extends('layouts.app')
@section('title', ($profession->exists ? 'Edit Profesi' : 'Tambah Profesi').' — Career Compass')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
    <script src="{{ asset('js/professions.js') }}?v={{ filemtime(public_path('js/professions.js')) }}" defer></script>
@endpush
@section('content')
@php
    $prospectRows = old('prospects', $profession->prospects) ?: [''];
    $responsibilityRows = old('responsibilities', $profession->responsibilities) ?: [''];
    $competencyRows = old('competency_ids', $profession->competencies->pluck('id')->all()) ?: [''];
@endphp
<div class="profession-page">
    <a class="back-link" href="{{ route('admin.professions.index') }}"><x-app.icon name="back" /> Kembali ke Manajemen Profesi</a>
    <div class="profession-heading">
        <div><h1>{{ $profession->exists ? 'Edit Profesi' : 'Tambah Profesi' }}</h1><p>Lengkapi informasi profesi untuk membantu mahasiswa mengeksplorasi pilihan karier.</p></div>
        <x-professions.status :profession="$profession" />
    </div>
    @if (session('status'))<div class="profession-notice profession-notice--success" role="status">{{ session('status') }}</div>@endif
    @if ($errors->any())
        <div class="profession-notice profession-notice--error" role="alert" tabindex="-1" id="validation-summary"><strong>Periksa kembali isian berikut.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><p>Jika memilih berkas tadi, pilih ulang sebelum menyimpan.</p></div>
    @endif
    <p class="required-note"><span aria-hidden="true">*</span> Wajib diisi untuk menerbitkan profesi. Kamu dapat menyimpan draf sebelum semua informasi lengkap.</p>
    <form method="POST" action="{{ $profession->exists ? route('admin.professions.update', $profession) : route('admin.professions.store') }}" enctype="multipart/form-data" data-profession-form>
        @csrf
        @if ($profession->exists) @method('PUT') @endif
        <section class="editor-section" aria-labelledby="basic-title">
            <div class="section-heading"><h2 id="basic-title"><span>1.</span> Informasi dasar</h2><p>Informasi ini muncul pada kartu dan halaman detail profesi.</p></div>
            <div class="editor-grid editor-grid--two">
                <x-professions.field name="name" label="Nama profesi" :required="true" :always-required="true" :value="old('name', $profession->name)" placeholder="Contoh: Data Analyst" maxlength="150" />
                <x-professions.field name="career_category_id" label="Kategori" type="select" :required="true" data-publish-required>
                    <option value="">Pilih kategori karier</option>
                    @foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('career_category_id', $profession->career_category_id) == $category->id)>{{ $category->name }}</option>@endforeach
                </x-professions.field>
            </div>
            <x-professions.field name="summary" label="Deskripsi singkat" type="textarea" :required="true" :value="old('summary', $profession->summary)" rows="2" maxlength="200" placeholder="Ringkasan singkat tentang profesi ini" hint="Ringkasan untuk daftar profesi. Maksimal 200 karakter." data-character-count="200" data-publish-required />
            <p class="character-counter" data-count-for="summary">{{ mb_strlen(old('summary', $profession->summary) ?? '') }}/200</p>
            <x-professions.field name="description" label="Deskripsi lengkap" type="textarea" :required="true" :value="old('description', $profession->description)" rows="5" maxlength="20000" placeholder="Jelaskan peran profesi, ruang lingkup pekerjaan, dan kontribusinya." data-publish-required />
            <div class="illustration-upload">
                <div class="illustration-preview" data-image-preview>
                    @if ($profession->illustration_path)<img src="{{ route('admin.professions.illustration', $profession) }}" alt="Ilustrasi profesi saat ini">@else<x-app.icon name="image" /><span>Ilustrasi</span>@endif
                </div>
                <div class="editor-field">
                    <label for="illustration">Ilustrasi profesi <span aria-hidden="true">*</span></label>
                    <p class="field-help" data-image-filename>{{ $profession->illustration_path ? 'Ilustrasi tersimpan. Pilih berkas untuk menggantinya.' : 'Belum ada berkas dipilih.' }}</p>
                    <input id="illustration" name="illustration" type="file" accept="image/png,image/jpeg" data-file-max="2097152" data-illustration data-existing-image="{{ $profession->illustration_path ? 'true' : 'false' }}" aria-describedby="illustration-help illustration-error">
                    <p class="field-help" id="illustration-help">PNG atau JPG, maksimal 2 MB. Rekomendasi rasio 3:2.</p>
                    @if ($profession->illustration_path)<label class="remove-image"><input type="checkbox" name="remove_illustration" value="1" @checked(old('remove_illustration'))> Hapus ilustrasi lama</label>@endif
                    <p id="illustration-error" class="editor-field-error" @unless ($errors->has('illustration')) hidden @endunless>{{ $errors->first('illustration') }}</p>
                </div>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="profile-title">
            <div class="section-heading"><h2 id="profile-title"><span>2.</span> Profil dan prospek karier</h2><p>Tentukan tingkat awal, rentang jenjang, dan peluang di industri.</p></div>
            <div class="editor-grid editor-grid--three">
                <x-professions.field name="entry_level" label="Tingkat awal" type="select" :required="true" data-publish-required>
                    <option value="">Pilih tingkat awal</option>@foreach (config('professions.entry_levels') as $level)<option @selected(old('entry_level', $profession->entry_level) === $level)>{{ $level }}</option>@endforeach
                </x-professions.field>
                <x-professions.field name="career_start" label="Jenjang awal" type="select" :required="true" data-publish-required>
                    <option value="">Pilih jenjang awal</option>@foreach (config('professions.career_levels') as $level)<option @selected(old('career_start', $profession->career_start) === $level)>{{ $level }}</option>@endforeach
                </x-professions.field>
                <x-professions.field name="career_end" label="Jenjang akhir" type="select" :required="true" data-publish-required>
                    <option value="">Pilih jenjang akhir</option>@foreach (config('professions.career_levels') as $level)<option @selected(old('career_end', $profession->career_end) === $level)>{{ $level }}</option>@endforeach
                </x-professions.field>
            </div>
            <x-professions.field name="industries_text" label="Bidang industri" :required="true" :value="old('industries_text', implode(', ', $profession->industries ?? []))" placeholder="Contoh: Teknologi, Keuangan, E-commerce" maxlength="3000" hint="Pisahkan industri dengan koma. Kamu dapat memasukkan lebih dari satu bidang." data-publish-required />
            @error('industries')<p class="editor-field-error">{{ $message }}</p>@enderror
            <div class="repeat-group" data-repeat-group="prospects" data-max-rows="20">
                <h3>Prospek karier <span aria-hidden="true">*</span></h3>
                @error('prospects')<p class="editor-field-error">{{ $message }}</p>@enderror
                <div data-repeat-rows>
                    @foreach (is_array($prospectRows) ? $prospectRows : [''] as $index => $value)<x-professions.list-row field="prospects" :index="$index" :value="$value" label="Prospek karier" />@endforeach
                </div>
                <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Prospek</button>
                <template><x-professions.list-row field="prospects" index="__INDEX__" label="Prospek karier" /></template>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="responsibilities-title">
            <div class="section-heading"><h2 id="responsibilities-title"><span>3.</span> Tanggung jawab dan kompetensi</h2><p>Tuliskan satu poin per baris. Pilih kompetensi dari master data yang tersedia.</p></div>
            <div class="editor-grid editor-grid--two repeat-columns">
                <div class="repeat-group" data-repeat-group="responsibilities" data-max-rows="50">
                    <h3>Tanggung jawab utama <span aria-hidden="true">*</span></h3>
                    @error('responsibilities')<p class="editor-field-error">{{ $message }}</p>@enderror
                    <div data-repeat-rows>@foreach (is_array($responsibilityRows) ? $responsibilityRows : [''] as $index => $value)<x-professions.list-row field="responsibilities" :index="$index" :value="$value" label="Tanggung jawab" />@endforeach</div>
                    <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Tanggung Jawab</button>
                    <template><x-professions.list-row field="responsibilities" index="__INDEX__" label="Tanggung jawab" /></template>
                </div>
                <div class="repeat-group" data-repeat-group="competency_ids" data-max-rows="100">
                    <h3>Kompetensi yang dibutuhkan <span aria-hidden="true">*</span></h3>
                    @error('competency_ids')<p class="editor-field-error">{{ $message }}</p>@enderror
                    <div data-repeat-rows>@foreach (is_array($competencyRows) ? $competencyRows : [''] as $index => $value)<x-professions.list-row field="competency_ids" :index="$index" :value="$value" :competencies="$competencies" label="Kompetensi" />@endforeach</div>
                    <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Kompetensi</button>
                    <template><x-professions.list-row field="competency_ids" index="__INDEX__" :competencies="$competencies" label="Kompetensi" /></template>
                </div>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="tools-title">
            <div class="section-heading"><h2 id="tools-title"><span>4.</span> Tools / teknologi</h2><p>Tambahkan tools beserta kegunaannya jika tersedia. Logo bersifat opsional, PNG/JPG maksimal 1 MB.</p></div>
            <div class="repeat-group" data-repeat-group="tools" data-max-rows="30">
                <div data-repeat-rows>@foreach (old('tools', $profession->tools ?? []) as $index => $tool)<x-professions.tool-row :index="$index" :tool="$tool" :profession="$profession" />@endforeach</div>
                <p class="tools-empty" data-empty-rows>Belum ada tool ditambahkan.</p>
                <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Tool / Teknologi</button>
                <template><x-professions.tool-row index="__INDEX__" :profession="$profession" /></template>
            </div>
        </section>

        <div class="editor-actions">
            <div><strong data-save-state>{{ $profession->exists && ! session()->hasOldInput() ? 'Semua perubahan tersimpan.' : 'Profesi belum disimpan.' }}</strong><p>Draf tidak ditampilkan kepada mahasiswa.</p></div>
            <div class="editor-action-buttons">
                <a class="button button--outline" href="{{ route('admin.professions.index') }}">Batal</a>
                <button class="button button--outline" type="submit" name="action" value="draft">Simpan Draf</button>
                <button class="button button--primary" type="submit" name="action" value="publish">{{ $profession->is_active && $profession->exists ? 'Simpan & Terbitkan' : 'Terbitkan Profesi' }}</button>
            </div>
            <span class="sr-only" role="status" data-editor-status></span>
        </div>
    </form>
</div>
@endsection
