@props(['profession', 'tone' => 0, 'filters' => []])
<article class="career-card">
    <a class="career-card-image" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}" tabindex="-1" aria-hidden="true"><x-careers.art :profession="$profession" :tone="$tone" /></a>
    <div class="career-card-content">
        <span class="category-badge">{{ $profession->careerCategory?->name ?? 'Kategori belum tersedia' }}</span>
        <h3><a href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">{{ $profession->name }}</a></h3>
        <p>{{ $profession->summary ?: Str::limit($profession->description, 145) }}</p>
        <a class="career-button career-button--outline" href="{{ route('careers.show', ['profession' => $profession, ...$filters]) }}">Lihat Detail <x-arrow /><span class="sr-only"> {{ $profession->name }}</span></a>
    </div>
</article>
