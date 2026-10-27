@extends('layouts.app')
@section('title', 'Manajemen Profesi — Career Compass')
@push('head')
<link rel="stylesheet" href="{{ asset('css/professions.css') }}?v={{ filemtime(public_path('css/professions.css')) }}">
<script src="{{ asset('js/professions.js') }}?v={{ filemtime(public_path('js/professions.js')) }}" defer></script>
@endpush
@section('content')
<div class="profession-page">
    <p class="profession-eyebrow">MANAJEMEN DATA</p>
    <div class="profession-heading"><div><h1>Manajemen Profesi</h1><p>Kelola informasi profesi untuk mendukung eksplorasi karier mahasiswa.</p></div><a class="button button--primary" href="{{ route('admin.professions.create') }}"><x-app.icon name="plus" /> Tambah Profesi</a></div>
    @if (session('status'))<p class="profession-notice profession-notice--success" role="status">{{ session('status') }}</p>@endif
    <section class="profession-list-card" aria-label="Daftar profesi">
        <form class="profession-filters" method="GET" action="{{ route('admin.professions.index') }}">
            <div class="search-field"><label class="sr-only" for="keyword">Cari nama profesi</label><x-app.icon name="search" /><input class="form-control" id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama profesi…" maxlength="150"></div>
            <div><label class="sr-only" for="status">Filter status</label><select class="form-control" id="status" name="status"><option value="">Semua status</option><option value="published" @selected(request('status') === 'published')>Terbit</option><option value="draft" @selected(request('status') === 'draft')>Draf</option><option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option></select></div>
            <button class="button button--outline" type="submit">Terapkan</button>
            @if (request()->filled('keyword') || request()->filled('status'))<a class="text-link" href="{{ route('admin.professions.index') }}">Reset</a>@endif
        </form>
        @if ($errors->any())<div class="profession-notice profession-notice--error" role="alert">{{ $errors->first() }}</div>@endif
        @if ($professions->isEmpty())
            <div class="profession-empty"><x-app.icon name="briefcase" /><h2>Tidak ada profesi yang ditemukan</h2><p>{{ request()->filled('keyword') || request()->filled('status') ? 'Coba kata kunci atau status yang lain.' : 'Mulai dengan menambahkan informasi profesi pertama.' }}</p></div>
        @else
            <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel profesi, geser untuk melihat semua kolom">
                <table class="profession-table"><caption class="sr-only">Daftar seluruh profesi, termasuk draf dan profesi nonaktif</caption><thead><tr><th scope="col">Profesi</th><th scope="col">Kategori</th><th scope="col">Status</th><th scope="col">Diperbarui</th><th scope="col">Aksi</th></tr></thead>
                <tbody>@foreach ($professions as $profession)<tr>
                    <td><a class="profession-name" href="{{ route('admin.professions.show', $profession) }}">{{ $profession->name }}</a><p class="profession-excerpt">{{ Str::limit($profession->summary ?: ($profession->description ?: 'Informasi profesi belum dilengkapi.'), 100) }}</p></td>
                    <td>{{ $profession->careerCategory?->name ?? 'Belum dipilih' }}</td>
                    <td><x-professions.status :profession="$profession" /></td>
                    <td class="date-cell">{{ $profession->updated_at->format('d M Y') }}</td>
                    <td><div class="row-actions"><a class="text-link" href="{{ route('admin.professions.show', $profession) }}">Detail<span class="sr-only"> {{ $profession->name }}</span></a><a class="text-link" href="{{ route('admin.professions.edit', $profession) }}">Edit<span class="sr-only"> {{ $profession->name }}</span></a>
                    @if ($profession->is_active)<form method="POST" action="{{ route('admin.professions.destroy', $profession) }}" data-deactivate-form data-profession-name="{{ $profession->name }}">@csrf @method('DELETE')<button class="text-link text-link--muted" type="submit">Nonaktifkan<span class="sr-only"> {{ $profession->name }}</span></button></form>@endif
                    </div></td>
                </tr>@endforeach</tbody></table>
            </div>
            <div class="profession-pagination"><span>Menampilkan {{ $professions->firstItem() }}–{{ $professions->lastItem() }} dari {{ $professions->total() }} profesi</span><nav aria-label="Pagination profesi">@if ($professions->onFirstPage())<span class="button button--small button--outline" aria-disabled="true">Sebelumnya</span>@else<a class="button button--small button--outline" href="{{ $professions->previousPageUrl() }}">Sebelumnya</a>@endif<span>Halaman {{ $professions->currentPage() }} / {{ $professions->lastPage() }}</span>@if ($professions->hasMorePages())<a class="button button--small button--outline" href="{{ $professions->nextPageUrl() }}">Berikutnya</a>@else<span class="button button--small button--outline" aria-disabled="true">Berikutnya</span>@endif</nav></div>
        @endif
    </section>
</div>
<dialog class="confirm-dialog" data-deactivate-dialog aria-labelledby="deactivate-title"><h2 id="deactivate-title">Nonaktifkan profesi?</h2><p data-deactivate-copy></p><p>Profesi tidak lagi tampil di katalog mahasiswa. Data dan relasinya tetap tersimpan.</p><div class="dialog-actions"><button class="button button--outline" type="button" data-cancel-deactivate>Batal</button><button class="button button--primary" type="button" data-confirm-deactivate>Nonaktifkan</button></div></dialog>
@endsection
