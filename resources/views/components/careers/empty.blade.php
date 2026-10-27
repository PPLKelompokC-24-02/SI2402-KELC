@props(['title' => 'Data belum tersedia', 'description' => null, 'icon' => 'book'])
<div {{ $attributes->class(['career-empty']) }}>
    <span class="empty-icon"><x-app.icon :name="$icon" /></span>
    <h3>{{ $title }}</h3>
    @if ($description)<p>{{ $description }}</p>@endif
    {{ $slot }}
</div>
