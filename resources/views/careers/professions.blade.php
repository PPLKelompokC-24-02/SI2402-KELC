@extends('layouts.app')
@section('title', 'Daftar Profesi — Career Compass')
<x-careers.assets />
@section('content')
<div class="career-page">
    <a class="career-back" href="{{ route('careers.index') }}"><x-app.icon name="back" /> Kembali ke Eksplorasi Karier</a>
    <div class="career-page-heading"><p class="career-eyebrow">CAREER EXPLORATION</p><h1>Daftar Profesi</h1><p>Temukan informasi profesi untuk memahami pilihan dan tujuan pengembangan kariermu.</p></div>
    <x-careers.search :keyword="request('keyword', '')" :category="request('category')" />
    <nav class="category-chips" aria-label="Filter kategori profesi">
        <a class="category-chip {{ ! request()->filled('category') ? 'is-selected' : '' }}" href="{{ route('careers.professions.index', array_filter(['keyword' => request('keyword')], fn ($value) => $value !== null)) }}" @unless (request()->filled('category')) aria-current="true" @endunless>Semua</a>
        @foreach ($categories as $category)<a class="category-chip {{ (string) request('category') === (string) $category->id ? 'is-selected' : '' }}" href="{{ route('careers.professions.index', array_filter(['category' => $category->id, 'keyword' => request('keyword')], fn ($value) => $value !== null)) }}" @if ((string) request('category') === (string) $category->id) aria-current="true" @endif>{{ $category->name }}</a>@endforeach
    </nav>
    <div class="catalog-results-heading"><p>@if ($professions->total())Menampilkan <strong>{{ $professions->firstItem() }}–{{ $professions->lastItem() }}</strong> dari <strong>{{ $professions->total() }}</strong> profesi@else Tidak ada hasil ditemukan @endif</p>@if (request()->filled('category') || request()->filled('keyword'))<a class="career-text-link" href="{{ route('careers.professions.index') }}">Reset pencarian</a>@endif</div>
    @if ($professions->isEmpty())<x-careers.empty title="Tidak ada profesi yang ditemukan" description="Coba kata kunci lain atau ubah kategori yang dipilih." icon="search"><a class="career-button career-button--outline" href="{{ route('careers.professions.index') }}">Lihat semua profesi</a></x-careers.empty>
    @else
        <div class="profession-result-list">
            @foreach ($professions as $profession)
                <article class="profession-result">
                    <a class="result-visual" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}" tabindex="-1" aria-hidden="true"><x-careers.art :profession="$profession" :tone="$loop->index" /></a>
                    <div class="result-copy"><div class="result-meta"><span class="category-badge">{{ $profession->careerCategory?->name ?? 'Kategori belum tersedia' }}</span>@if ($profession->entry_level)<span class="level-badge">{{ $profession->entry_level }}</span>@endif</div><h2><a href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">{{ $profession->name }}</a></h2><p>{{ $profession->summary ?: Str::limit($profession->description, 180) }}</p></div>
                    <a class="result-detail-link" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}"><span>Lihat Detail</span><x-arrow /><span class="sr-only"> {{ $profession->name }}</span></a>
                </article>
            @endforeach
        </div>
        <nav class="career-pagination" aria-label="Pagination daftar profesi">
            @if ($professions->onFirstPage())<span class="career-button career-button--outline" aria-disabled="true">Sebelumnya</span>@else<a class="career-button career-button--outline" href="{{ $professions->previousPageUrl() }}">Sebelumnya</a>@endif
            <span>Halaman {{ $professions->currentPage() }} dari {{ $professions->lastPage() }}</span>
            @if ($professions->hasMorePages())<a class="career-button career-button--outline" href="{{ $professions->nextPageUrl() }}">Berikutnya <x-arrow /></a>@else<span class="career-button career-button--outline" aria-disabled="true">Berikutnya</span>@endif
        </nav>
    @endif
</div>
@endsection
