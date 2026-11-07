@extends('layouts.app')
@section('title', 'Template Aktivitas — Career Compass')
@push('head')
<link rel="stylesheet" href="{{ asset('css/action-templates.css') }}?v={{ filemtime(public_path('css/action-templates.css')) }}">
<script src="{{ asset('js/action-templates.js') }}?v={{ filemtime(public_path('js/action-templates.js')) }}" defer></script>
@endpush
@section('content')
<div class="template-page">
    <p class="template-eyebrow">CAREER ACTION PLAN</p>
    <div class="template-heading">
        <div>
            <h1>Template Aktivitas</h1>
            <p>Kelola master template aktivitas pengembangan kompetensi untuk referensi mahasiswa saat menyusun Career Action Plan.</p>
        </div>
        <a class="button button--primary" href="{{ route('admin.action-templates.create') }}">
            <x-app.icon name="plus" /> Tambah Template
        </a>
    </div>

    @if (session('status'))
        <div class="template-notice template-notice--success" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="template-notice template-notice--error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="template-list-card" aria-label="Daftar template aktivitas">
        <form class="template-filters" method="GET" action="{{ route('admin.action-templates.index') }}">
            <div class="search-field">
                <label class="sr-only" for="keyword">Cari judul atau deskripsi template</label>
                <x-app.icon name="search" />
                <input class="form-control" id="keyword" type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul atau isi template…" maxlength="150">
            </div>
            <div>
                <label class="sr-only" for="status">Filter status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">Semua status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </div>
            <button class="button button--outline" type="submit">Terapkan</button>
            @if (request()->filled('keyword') || request()->filled('status'))
                <a class="text-link" href="{{ route('admin.action-templates.index') }}">Reset</a>
            @endif
        </form>

        @if ($templates->isEmpty())
            <div class="template-empty">
                <x-app.icon name="plan" />
                <h2>{{ request()->filled('keyword') || request()->filled('status') ? 'Tidak ada template yang cocok' : 'Belum ada template aktivitas' }}</h2>
                <p>{{ request()->filled('keyword') || request()->filled('status') ? 'Silakan coba kata kunci pencarian atau status filter yang lain.' : 'Mulai dengan menambahkan template aktivitas pengembangan diri pertama.' }}</p>
            </div>
        @else
            <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel template aktivitas, geser untuk melihat semua kolom">
                <table class="template-table">
                    <caption class="sr-only">Daftar master template aktivitas pengembangan diri</caption>
                    <thead>
                        <tr>
                            <th scope="col" style="min-width: 280px;">Judul Template</th>
                            <th scope="col" style="width: 140px;">Status</th>
                            <th scope="col" style="width: 160px;">Diperbarui</th>
                            <th scope="col" style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr>
                                <td>
                                    <a class="template-name" href="{{ route('admin.action-templates.show', $template) }}">
                                        {{ $template->title }}
                                    </a>
                                    <p class="template-excerpt">
                                        {{ Str::limit($template->description, 130) }}
                                    </p>
                                </td>
                                <td>
                                    <span class="template-status {{ $template->is_active ? 'template-status--active' : 'template-status--inactive' }}">
                                        <span></span>{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="date-cell">
                                    {{ $template->updated_at ? $template->updated_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="text-link" href="{{ route('admin.action-templates.show', $template) }}">
                                            Detail<span class="sr-only"> {{ $template->title }}</span>
                                        </a>
                                        <a class="text-link" href="{{ route('admin.action-templates.edit', $template) }}">
                                            Edit<span class="sr-only"> {{ $template->title }}</span>
                                        </a>

                                        @if ($template->is_active)
                                            <form method="POST" action="{{ route('admin.action-templates.destroy', $template) }}"
                                                  data-confirm-action
                                                  data-confirm-title="Nonaktifkan Template?"
                                                  data-confirm-message="Template '{{ $template->title }}' tidak akan lagi tersedia sebagai referensi baru untuk mahasiswa. Aktivitas mahasiswa yang sudah dibuat dari template ini tetap aman dan tidak akan terhapus."
                                                  data-confirm-submit="Nonaktifkan">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="action" value="deactivate">
                                                <button class="text-link text-link--muted" type="submit">
                                                    Nonaktifkan<span class="sr-only"> {{ $template->title }}</span>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.action-templates.toggle-status', $template) }}">
                                                @csrf
                                                <button class="text-link text-link--success" type="submit">
                                                    Aktifkan<span class="sr-only"> {{ $template->title }}</span>
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.action-templates.destroy', $template) }}"
                                              data-confirm-action
                                              data-confirm-title="Hapus Template?"
                                              data-confirm-message="Template '{{ $template->title }}' akan dihapus secara permanen dari master template. Aktivitas mahasiswa yang telah dibuat sebelumnya tetap aman dan tidak terhapus."
                                              data-confirm-submit="Hapus Permanen">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="delete">
                                            <button class="text-link text-link--muted" type="submit">
                                                Hapus<span class="sr-only"> {{ $template->title }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="template-pagination">
                <span>Menampilkan {{ $templates->firstItem() }}–{{ $templates->lastItem() }} dari {{ $templates->total() }} template</span>
                <nav aria-label="Pagination template aktivitas">
                    @if ($templates->onFirstPage())
                        <span class="button button--small button--outline" aria-disabled="true">Sebelumnya</span>
                    @else
                        <a class="button button--small button--outline" href="{{ $templates->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    <span>Halaman {{ $templates->currentPage() }} / {{ $templates->lastPage() }}</span>
                    @if ($templates->hasMorePages())
                        <a class="button button--small button--outline" href="{{ $templates->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="button button--small button--outline" aria-disabled="true">Berikutnya</span>
                    @endif
                </nav>
            </div>
        @endif
    </section>
</div>

<dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="dialog-title">
    <h2 id="dialog-title" data-dialog-title>Konfirmasi Tindakan</h2>
    <p data-dialog-message>Apakah Anda yakin ingin memproses tindakan ini?</p>
    <div class="dialog-actions">
        <button class="button button--outline" type="button" data-dialog-cancel>Batal</button>
        <button class="button button--primary" type="button" data-dialog-submit>Lanjutkan</button>
    </div>
</dialog>
@endsection

