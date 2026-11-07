@props(['profession', 'tone' => 0, 'filters' => [], 'isSaved' => false])
<article class="career-card" data-profession-card="{{ $profession->id }}">
    <div class="career-card-top">
        <a class="career-card-image" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}" tabindex="-1" aria-hidden="true"><x-careers.art :profession="$profession" :tone="$tone" /></a>
        <form method="POST" action="{{ route('careers.favorite.toggle', $profession) }}" class="favorite-form favorite-card-form" data-favorite-form data-profession-id="{{ $profession->id }}" data-profession-name="{{ $profession->name }}">
            @csrf
            <button type="submit" class="favorite-action-btn favorite-card-btn {{ $isSaved ? 'is-active' : '' }}" title="{{ $isSaved ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}" aria-label="{{ $isSaved ? 'Hapus '.$profession->name.' dari Favorit' : 'Simpan '.$profession->name.' ke Favorit' }}">
                <x-app.icon :name="$isSaved ? 'heart-filled' : 'heart'" class="heart-icon" />
            </button>
        </form>
    </div>
    <div class="career-card-content">
        <span class="category-badge">{{ $profession->careerCategory?->name ?? 'Kategori belum tersedia' }}</span>
        <h3><a href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">{{ $profession->name }}</a></h3>
        <p>{{ $profession->summary ?: Str::limit($profession->description, 145) }}</p>
        <a class="career-button career-button--outline" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">Lihat Detail <x-arrow /><span class="sr-only"> {{ $profession->name }}</span></a>
    </div>
</article>
