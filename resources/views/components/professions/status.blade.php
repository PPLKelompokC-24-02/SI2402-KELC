@props(['profession'])
<span class="profession-status profession-status--{{ ! $profession->exists || $profession->publication_status === 'draft' ? 'draft' : ($profession->is_active ? 'published' : 'inactive') }}">
    <span aria-hidden="true"></span>{{ ! $profession->exists ? 'Belum disimpan' : ($profession->publication_status === 'draft' ? 'Draf' : ($profession->is_active ? 'Terbit' : 'Nonaktif')) }}
</span>
