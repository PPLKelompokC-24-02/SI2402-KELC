@extends('layouts.app')
@section('title', 'Career Exploration & Career Path — Career Compass')
<x-careers.assets />
@section('content')
<div class="career-page">
    <section class="exploration-hero" aria-labelledby="exploration-title">
        <div class="exploration-hero-copy"><p class="career-eyebrow">CAREER EXPLORATION & CAREER PATH</p><h1 id="exploration-title">Kenali pilihanmu.<br>Temukan arah <em>kariermu.</em></h1><p>Jelajahi berbagai profesi, pahami kompetensinya, dan mulai rencanakan langkah pengembangan dirimu.</p></div>
        <div class="exploration-hero-art" aria-hidden="true"><x-compass /><span>Setiap langkah punya arah.</span></div>
    </section>
    <div class="exploration-search"><x-careers.search /></div>
    <section class="category-section" aria-labelledby="category-title">
        <div class="career-section-heading"><h2 id="category-title">Jelajahi bidang karier</h2><p>Mulai dari bidang yang ingin kamu kenali.</p></div>
        <div class="category-grid">
            <a class="category-card category-card--all" href="{{ route('careers.professions.index') }}"><span class="category-icon"><x-compass /></span><span>Semua profesi</span><x-arrow /></a>
            @foreach ($categories as $category)
                <a class="category-card category-card--{{ $loop->index % 4 }}" href="{{ route('careers.professions.index', ['category' => $category->id]) }}"><span class="category-icon"><x-app.icon :name="match ($loop->index % 4) { 0 => 'briefcase', 1 => 'data', 2 => 'design', default => 'code' }" /></span><span>{{ $category->name }}</span><x-arrow /></a>
            @endforeach
        </div>
    </section>
    <section aria-labelledby="featured-title" class="featured-section">
        <div class="career-section-heading career-section-heading--inline"><div><h2 id="featured-title">Pilihan profesi untuk dijelajahi</h2><p>Kenali peran, tanggung jawab, dan kompetensi yang dibutuhkan.</p></div><a class="career-text-link" href="{{ route('careers.professions.index') }}">Lihat semua <x-arrow /></a></div>
        @if ($professions->isNotEmpty())<div class="career-card-grid">@foreach ($professions as $profession)<x-careers.card :profession="$profession" :tone="$loop->index" />@endforeach</div>
        @else<x-careers.empty title="Belum ada profesi tersedia" description="Informasi profesi akan tampil setelah diterbitkan." icon="briefcase" />@endif
    </section>
</div>
@endsection
