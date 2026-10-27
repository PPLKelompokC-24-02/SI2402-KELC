@extends('layouts.app')
@section('title', $profession->name.' — Career Compass')
@push('head')<link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">@endpush
@section('content')
<div class="profession-page profession-detail">
    <a class="back-link" href="{{ route('admin.professions.index') }}"><x-app.icon name="back" /> Kembali ke Manajemen Profesi</a>
    <div class="profession-heading"><div><h1>{{ $profession->name }}</h1><p>{{ $profession->careerCategory?->name ?? 'Kategori belum dipilih' }}</p></div><div class="detail-heading-actions"><x-professions.status :profession="$profession" /><a class="button button--primary" href="{{ route('admin.professions.edit', $profession) }}">Edit Profesi</a></div></div>
    <section class="editor-section detail-overview" aria-labelledby="overview-title">
        @if ($profession->illustration_path)<img class="detail-illustration" src="{{ route('admin.professions.illustration', $profession) }}" alt="Ilustrasi {{ $profession->name }}">@endif
        <div><h2 id="overview-title">Tentang profesi</h2><p class="detail-summary">{{ $profession->summary ?: 'Deskripsi singkat belum diisi.' }}</p><p class="detail-description">{{ $profession->description ?: 'Deskripsi lengkap belum diisi.' }}</p></div>
    </section>
    <section class="editor-section" aria-labelledby="detail-profile"><h2 id="detail-profile">Profil dan prospek karier</h2><dl class="detail-facts"><div><dt>Tingkat awal</dt><dd>{{ $profession->entry_level ?: 'Belum diisi' }}</dd></div><div><dt>Rentang jenjang</dt><dd>{{ $profession->career_start ?: '—' }} → {{ $profession->career_end ?: '—' }}</dd></div><div><dt>Bidang industri</dt><dd>{{ implode(', ', $profession->industries ?? []) ?: 'Belum diisi' }}</dd></div></dl><h3>Prospek karier</h3><ul class="detail-list">@forelse ($profession->prospects ?? [] as $prospect)<li>{{ $prospect }}</li>@empty<li>Belum diisi.</li>@endforelse</ul></section>
    <section class="editor-section editor-grid editor-grid--two"><div><h2>Tanggung jawab utama</h2><ul class="detail-list">@forelse ($profession->responsibilities ?? [] as $responsibility)<li>{{ $responsibility }}</li>@empty<li>Belum diisi.</li>@endforelse</ul></div><div><h2>Kompetensi yang dibutuhkan</h2><ul class="detail-list">@forelse ($profession->competencies as $competency)<li><strong>{{ $competency->name }}</strong><span class="competency-description">{{ $competency->description }}</span></li>@empty<li>Belum dipilih.</li>@endforelse</ul></div></section>
    <section class="editor-section"><h2>Tools / teknologi</h2><div class="detail-tools">@forelse ($profession->tools ?? [] as $tool)<div class="detail-tool">@if (! empty($tool['logo_path']))<img src="{{ route('admin.professions.tool-logo', [$profession, $tool['key']]) }}" alt="Logo {{ $tool['name'] }}" width="40" height="40">@endif<div><h3>{{ $tool['name'] ?: 'Tool belum diberi nama' }}</h3><p>{{ $tool['usage'] ?: 'Kegunaan belum diisi.' }}</p></div></div>@empty<p class="field-help">Belum ada tool ditambahkan.</p>@endforelse</div></section>
</div>
@endsection
