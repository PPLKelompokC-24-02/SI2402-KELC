@extends('layouts.app')
@section('title', ($tab === 'favorites' ? 'Favorit Saya' : 'Daftar Profesi') . ' — Career Compass')
<x-careers.assets />
@section('content')
<div class="career-page">
    <a class="career-back" href="{{ route('careers.index') }}"><x-app.icon name="back" /> Kembali ke Eksplorasi Karier</a>

    <div id="toast-container" class="toast-container" aria-live="polite">
        @if (session('status'))
            <div class="toast-message toast-message--success" role="status">
                <span class="toast-icon"><x-app.icon name="check" /></span>
                <span class="toast-text">{{ session('status') }}</span>
                <button type="button" class="toast-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
            </div>
        @endif
        @if (session('info'))
            <div class="toast-message toast-message--info" role="status">
                <span class="toast-icon"><x-app.icon name="heart" /></span>
                <span class="toast-text">{{ session('info') }}</span>
                <button type="button" class="toast-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="toast-message toast-message--error" role="alert">
                <span class="toast-icon"><x-app.icon name="close" /></span>
                <span class="toast-text">{{ session('error') }}</span>
                <button type="button" class="toast-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
            </div>
        @endif
    </div>

    <div class="career-page-heading">
        <p class="career-eyebrow">CAREER EXPLORATION</p>
        @if ($tab === 'favorites')
            <h1>Favorit Saya</h1>
            <p>Profesi yang telah kamu simpan sebagai referensi target karier.</p>
        @else
            <h1>Daftar Profesi</h1>
            <p>Temukan informasi profesi untuk memahami pilihan dan tujuan pengembangan kariermu.</p>
        @endif
    </div>

    <nav class="career-tabs" aria-label="Tab Eksplorasi Karier">
        <a class="career-tab {{ $tab === 'all' ? 'is-active' : '' }}"
           href="{{ route('careers.professions.index', array_filter(['tab' => 'all', 'category' => request('category'), 'keyword' => request('keyword')])) }}"
           @if ($tab === 'all') aria-current="page" @endif>
            Daftar Profesi
        </a>
        <a class="career-tab {{ $tab === 'favorites' ? 'is-active' : '' }}"
           href="{{ route('careers.professions.index', ['tab' => 'favorites']) }}"
           @if ($tab === 'favorites') aria-current="page" @endif>
            <span class="tab-heart-icon"><x-app.icon name="heart-filled" /></span>
            <span>Favorit Saya</span>
            @if ($savedCount > 0)
                <span class="tab-badge" data-saved-count>{{ $savedCount }}</span>
            @endif
        </a>
    </nav>

    <x-careers.search :keyword="request('keyword', '')" :category="request('category')" :tab="$tab" />

    <nav class="category-chips" aria-label="Filter kategori profesi">
        <a class="category-chip {{ ! request()->filled('category') ? 'is-selected' : '' }}"
           href="{{ route('careers.professions.index', array_filter(['tab' => $tab, 'keyword' => request('keyword')], fn ($value) => $value !== null)) }}"
           @unless (request()->filled('category')) aria-current="true" @endunless>Semua</a>
        @foreach ($categories as $category)
            <a class="category-chip {{ (string) request('category') === (string) $category->id ? 'is-selected' : '' }}"
               href="{{ route('careers.professions.index', array_filter(['tab' => $tab, 'category' => $category->id, 'keyword' => request('keyword')], fn ($value) => $value !== null)) }}"
               @if ((string) request('category') === (string) $category->id) aria-current="true" @endif>{{ $category->name }}</a>
        @endforeach
    </nav>

    <div class="catalog-results-heading">
        <p>
            @if ($professions->total())
                Menampilkan <strong>{{ $professions->firstItem() }}–{{ $professions->lastItem() }}</strong> dari <strong>{{ $professions->total() }}</strong> profesi
            @else
                Tidak ada hasil ditemukan
            @endif
        </p>
        @if (request()->filled('category') || request()->filled('keyword'))
            <a class="career-text-link" href="{{ route('careers.professions.index', ['tab' => $tab]) }}">Reset pencarian</a>
        @endif
    </div>

    @if ($professions->isEmpty())
        @if ($tab === 'favorites')
            @if (request()->filled('category') || request()->filled('keyword'))
                <x-careers.empty title="Tidak ada profesi favorit yang cocok" description="Coba kata kunci lain atau ubah kategori yang dipilih." icon="search">
                    <a class="career-button career-button--outline" href="{{ route('careers.professions.index', ['tab' => 'favorites']) }}">Lihat semua favorit</a>
                </x-careers.empty>
            @else
                <x-careers.empty title="Belum ada profesi favorit." description="Jelajahi daftar profesi dan simpan profesi yang menarik minatmu." icon="heart">
                    <a class="career-button career-button--primary" href="{{ route('careers.professions.index', ['tab' => 'all']) }}">Jelajahi Daftar Profesi</a>
                </x-careers.empty>
            @endif
        @else
            <x-careers.empty title="Tidak ada profesi yang ditemukan" description="Coba kata kunci lain atau ubah kategori yang dipilih." icon="search">
                <a class="career-button career-button--outline" href="{{ route('careers.professions.index', ['tab' => 'all']) }}">Lihat semua profesi</a>
            </x-careers.empty>
        @endif
    @else
        <div class="profession-result-list" data-profession-list>
            @foreach ($professions as $profession)
                @php
                    $isSaved = in_array($profession->id, $savedProfessionIds, true);
                @endphp
                <article class="profession-result" data-profession-card="{{ $profession->id }}">
                    <a class="result-visual" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}" tabindex="-1" aria-hidden="true">
                        <x-careers.art :profession="$profession" :tone="$loop->index" />
                    </a>
                    <div class="result-copy">
                        <div class="result-meta">
                            <span class="category-badge">{{ $profession->careerCategory?->name ?? 'Kategori belum tersedia' }}</span>
                            @if ($profession->entry_level)
                                <span class="level-badge">{{ $profession->entry_level }}</span>
                            @endif
                        </div>
                        <h2>
                            <a href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">{{ $profession->name }}</a>
                        </h2>
                        <p>{{ $profession->summary ?: Str::limit($profession->description, 180) }}</p>
                    </div>
                    <div class="result-actions">
                        <form method="POST"
                              action="{{ route('careers.favorite.toggle', $profession) }}"
                              class="favorite-form"
                              data-favorite-form
                              data-profession-id="{{ $profession->id }}"
                              data-profession-name="{{ $profession->name }}">
                            @csrf
                            <button type="submit"
                                    class="favorite-action-btn {{ $isSaved ? 'is-active' : '' }}"
                                    title="{{ $isSaved ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}"
                                    aria-label="{{ $isSaved ? 'Hapus '.$profession->name.' dari Favorit' : 'Simpan '.$profession->name.' ke Favorit' }}">
                                <x-app.icon :name="$isSaved ? 'heart-filled' : 'heart'" class="heart-icon" />
                            </button>
                        </form>
                        <a class="result-detail-link" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">
                            <span>Lihat Detail</span>
                            <x-arrow />
                            <span class="sr-only"> {{ $profession->name }}</span>
                        </a>
                        @if ($tab === 'favorites')
                            <form method="POST"
                                  action="{{ route('careers.favorite.destroy', $profession) }}"
                                  class="favorite-destroy-form"
                                  data-favorite-form
                                  data-profession-id="{{ $profession->id }}"
                                  data-profession-name="{{ $profession->name }}"
                                  data-is-destroy="true">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorite-remove-btn" title="Hapus dari Favorit Saya">
                                    <x-app.icon name="trash" />
                                    <span>Hapus Favorit</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <nav class="career-pagination" aria-label="Pagination daftar profesi">
            @if ($professions->onFirstPage())
                <span class="career-button career-button--outline" aria-disabled="true">Sebelumnya</span>
            @else
                <a class="career-button career-button--outline" href="{{ $professions->previousPageUrl() }}">Sebelumnya</a>
            @endif
            <span>Halaman {{ $professions->currentPage() }} dari {{ $professions->lastPage() }}</span>
            @if ($professions->hasMorePages())
                <a class="career-button career-button--outline" href="{{ $professions->nextPageUrl() }}">Berikutnya <x-arrow /></a>
            @else
                <span class="career-button career-button--outline" aria-disabled="true">Berikutnya</span>
            @endif
        </nav>
    @endif
</div>
@endsection
