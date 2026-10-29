@extends('layouts.app')
@section('title', $profession->name.' — Career Compass')
<x-careers.assets />
@section('content')
<div class="career-page career-detail-page">
    <a class="career-back" href="{{ route('careers.professions.index', $filters) }}"><x-app.icon name="back" /> Kembali ke Daftar Profesi</a>
    <header class="profession-hero {{ $tab !== 'overview' ? 'profession-hero--compact' : '' }}">
        <div class="profession-hero-copy"><p class="career-eyebrow">KENALI PROFESINYA</p><span class="category-badge">{{ $profession->careerCategory?->name ?? 'Kategori belum tersedia' }}</span><h1>{{ $profession->name }}</h1>@if ($tab === 'overview')<p>{{ $profession->summary ?: Str::limit($profession->description, 240) }}</p>@endif</div>
        @if ($tab === 'overview')<x-careers.art :profession="$profession" />@endif
    </header>
    @if ($tab === 'overview')
    <dl class="profession-facts">
        <div class="profession-fact"><span class="fact-icon"><x-app.icon name="progress" /></span><div><dt>Tingkat awal</dt><dd>{{ $profession->entry_level ?: 'Data belum tersedia' }}</dd></div></div>
        <div class="profession-fact"><span class="fact-icon"><x-compass /></span><div><dt>Jenjang karier</dt><dd>{{ $profession->career_start && $profession->career_end ? $profession->career_start.' – '.$profession->career_end : 'Data belum tersedia' }}</dd></div></div>
        <div class="profession-fact"><span class="fact-icon"><x-app.icon name="briefcase" /></span><div><dt>Bidang industri</dt><dd>{{ implode(', ', $profession->industries ?? []) ?: 'Data belum tersedia' }}</dd></div></div>
    </dl>
    @endif
    <nav class="career-detail-nav" aria-label="Bagian informasi profesi">
        @foreach ($tabs as $key => $label)<a href="{{ route('careers.show', ['profession' => $profession, ...$filters, 'tab' => $key]) }}" class="detail-tab {{ $tab === $key ? 'is-active' : '' }}" @if ($tab === $key) aria-current="page" @endif>{{ $label }}</a>@endforeach
    </nav>
    <section class="detail-tab-content" aria-labelledby="detail-section-title">
        @switch($tab)
            @case('overview')
                <div class="career-section-heading"><h2 id="detail-section-title">Deskripsi Profesi</h2></div>
                <div class="career-content-card"><p class="profession-description">{{ $profession->description ?: 'Deskripsi profesi belum tersedia.' }}</p></div>
                <div class="detail-two-columns">
                    <section class="career-content-card"><h3><span class="content-heading-icon"><x-app.icon name="progress" /></span>Prospek Karier</h3>@if ($profession->prospects)<ul class="check-list">@foreach ($profession->prospects as $prospect)<li><x-app.icon name="check" /><span>{{ $prospect }}</span></li>@endforeach</ul>@else<p class="content-empty-copy">Informasi prospek karier belum tersedia.</p>@endif</section>
                    <section class="career-content-card"><h3><span class="content-heading-icon"><x-app.icon name="briefcase" /></span>Industri yang Membutuhkan</h3>@if ($profession->industries)<ul class="industry-list">@foreach ($profession->industries as $industry)<li>{{ $industry }}</li>@endforeach</ul>@else<p class="content-empty-copy">Informasi bidang industri belum tersedia.</p>@endif</section>
                </div>
                @break
            @case('responsibilities')
                <div class="career-section-heading"><h2 id="detail-section-title">Tanggung Jawab Utama</h2><p>Kenali pekerjaan dan kontribusi yang dilakukan dalam profesi {{ $profession->name }}.</p></div>
                @if ($profession->responsibilities)<ol class="responsibility-list">@foreach ($profession->responsibilities as $responsibility)<li><span class="responsibility-number" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><p>{{ $responsibility }}</p></li>@endforeach</ol>@else<x-careers.empty title="Data belum tersedia" description="Informasi tanggung jawab profesi belum ditambahkan." icon="assessment" />@endif
                @break
            @case('competencies')
                <div class="career-section-heading"><h2 id="detail-section-title">Kompetensi yang Dibutuhkan</h2><p>Gunakan informasi ini untuk mengenali keterampilan yang ingin kamu kembangkan.</p></div>
                @if ($profession->competencies->isNotEmpty())<div class="competency-grid">@foreach ($profession->competencies as $competency)<article class="career-content-card competency-card"><span class="competency-symbol"><x-app.icon name="check" /></span><div><h3>{{ $competency->name }}</h3><p>{{ $competency->description ?: 'Deskripsi kompetensi belum tersedia.' }}</p></div></article>@endforeach</div>@else<x-careers.empty title="Data belum tersedia" description="Informasi kompetensi profesi belum ditambahkan." icon="progress" />@endif
                @break
            @case('path')
                <div class="career-section-heading"><h2 id="detail-section-title">Jalur Karier</h2><p>Kenali rentang jenjang karier yang tersedia untuk profesi {{ $profession->name }}.</p></div>
                @if ($profession->career_start && $profession->career_end)
                    <div class="career-path-range"><article class="path-step"><span class="path-step-label">JENJANG AWAL</span><span class="path-step-icon"><x-compass /></span><h3>{{ $profession->career_start }}</h3>@if ($profession->entry_level)<p>{{ $profession->entry_level }}</p>@endif</article><span class="path-connector" aria-hidden="true"><x-arrow /></span><article class="path-step path-step--end"><span class="path-step-label">JENJANG AKHIR</span><span class="path-step-icon"><x-app.icon name="progress" /></span><h3>{{ $profession->career_end }}</h3><p>Rentang jenjang untuk profesi ini</p></article></div>
                    <div class="career-info-note"><x-app.icon name="compass" /><p>Rincian tahapan, jabatan, dan pengalaman yang dibutuhkan belum tersedia. Rentang jenjang ini merupakan informasi umum profesi.</p></div>
                @else<x-careers.empty title="Data jalur karier belum tersedia" description="Informasi jenjang dan tahapan karier akan ditampilkan setelah tersedia." icon="compass" />@endif
                @break
            @case('courses')
                <div class="career-section-heading"><h2 id="detail-section-title">Mata Kuliah yang Relevan</h2><p>Informasi mata kuliah yang mendukung pengembangan kompetensi profesi {{ $profession->name }}.</p></div>
                <x-careers.empty title="Data belum tersedia" description="Informasi mata kuliah yang relevan akan ditambahkan ketika data tersedia." icon="book" />
                @break
            @case('tools')
                <div class="career-section-heading"><h2 id="detail-section-title">Tools & Teknologi</h2><p>Kenali tools dan kegunaannya dalam pekerjaan {{ $profession->name }}.</p></div>
                @if ($profession->tools)
                    <div class="tools-grid">@foreach ($profession->tools as $tool)
                        <article class="tool-card"><div class="tool-visual">@if (! empty($tool['logo_path']) && Storage::disk('local')->exists($tool['logo_path']))<img src="{{ route('careers.tool-logo', [$profession, $tool['key']]) }}" alt="Logo {{ $tool['name'] }}" loading="lazy" width="160" height="100">@else<span class="tool-fallback" aria-hidden="true"><x-app.icon name="code" /></span>@endif</div><div class="tool-copy"><h3>{{ $tool['name'] }}</h3><p>{{ $tool['usage'] }}</p></div></article>
                    @endforeach</div>
                @else<x-careers.empty title="Data tools belum tersedia" description="Informasi tools dan teknologi profesi belum ditambahkan." icon="code" />@endif
                @break
        @endswitch
    </section>
</div>
@endsection
