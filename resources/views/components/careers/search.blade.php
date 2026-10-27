@props(['keyword' => '', 'category' => null])
<form class="career-search" method="GET" action="{{ route('careers.professions.index') }}" role="search">
    <label class="sr-only" for="career-keyword">Cari nama profesi</label>
    <x-app.icon name="search" />
    <input id="career-keyword" type="search" name="keyword" value="{{ is_string($keyword) ? $keyword : '' }}" maxlength="150" placeholder="Cari profesi, misal: data analyst, UI/UX, business analyst…" autocomplete="off">
    @if ($category)<input type="hidden" name="category" value="{{ $category }}">@endif
    <button class="career-button career-button--primary" type="submit">Cari <x-arrow /></button>
</form>
