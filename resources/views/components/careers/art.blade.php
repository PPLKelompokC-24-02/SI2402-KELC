@props(['profession', 'tone' => 0])
@php
    $categoryName = mb_strtolower($profession->careerCategory?->name ?? '');
    $icon = match (true) {
        str_contains($categoryName, 'data') => 'data',
        str_contains($categoryName, 'software') => 'code',
        str_contains($categoryName, 'design') => 'design',
        default => 'briefcase',
    };
    $hasImage = $profession->illustration_path && Storage::disk('local')->exists($profession->illustration_path);
@endphp
<div {{ $attributes->class(['career-art', 'career-art--'.($tone % 4)]) }}>
    @if ($hasImage)
        <img src="{{ route('careers.illustration', $profession) }}" alt="Ilustrasi {{ $profession->name }}" loading="lazy" width="600" height="400">
    @else
        <div class="career-art-fallback" aria-hidden="true"><span class="art-orbit art-orbit--outer"></span><span class="art-orbit art-orbit--inner"></span><span class="art-symbol"><x-app.icon :name="$icon" /></span><x-arrow data-direction="diagonal" class="art-arrow" /><span class="art-signature">CAREER COMPASS</span></div>
    @endif
</div>
