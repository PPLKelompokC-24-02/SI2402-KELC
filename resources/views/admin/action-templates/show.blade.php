@extends('layouts.app')
@section('title', $template->title . ' — Career Compass')
@push('head')
<link rel="stylesheet" href="{{ asset('css/action-templates.css') }}?v={{ filemtime(public_path('css/action-templates.css')) }}">
<script src="{{ asset('js/action-templates.js') }}?v={{ filemtime(public_path('js/action-templates.js')) }}" defer></script>
@endpush
@section('content')
<div class="template-page">
    <a class="back-link" href="{{ route('admin.action-templates.index') }}">
        <x-app.icon name="back" /> Kembali ke daftar template
    </a>

    <div class="template-heading">
        <div>
            <p class="template-eyebrow">Detail Template Aktivitas</p>
            <h1>{{ $template->title }}</h1>
        </div>
        <div>
            <span class="template-status {{ $template->is_active ? 'template-status--active' : 'template-status--inactive' }}">
                <span></span>{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    @if (session('status'))
        <div class="template-notice template-notice--success" role="status">
            {{ session('status') }}
        </div>
    @endif

    <div class="detail-card">
        <div class="detail-meta-grid">
            <div>
                <div class="meta-item-label">Status Visibilitas</div>
                <div class="meta-item-value">{{ $template->is_active ? 'Aktif (Dapat dilihat mahasiswa)' : 'Nonaktif (Disembunyikan)' }}</div>
            </div>
            <div>
                <div class="meta-item-label">Dibuat Pada</div>
                <div class="meta-item-value">{{ $template->created_at ? $template->created_at->format('d M Y H:i') : '-' }}</div>
            </div>
            <div>
                <div class="meta-item-label">Terakhir Diperbarui</div>
                <div class="meta-item-value">{{ $template->updated_at ? $template->updated_at->format('d M Y H:i') : '-' }}</div>
            </div>
            <div>
                <div class="meta-item-label">Penggunaan Mahasiswa</div>
                <div class="meta-item-value">{{ $template->actionPlans()->count() }} Aktivitas Dibuat</div>
            </div>
        </div>

        <div class="detail-body">
            <h2>Deskripsi Aktivitas</h2>
            <p>{{ $template->description }}</p>
        </div>

        <div class="info-box">
            <x-app.icon name="plan" />
            <div>
                <strong>Integritas Data Mahasiswa:</strong>
                <br>
                Template ini berfungsi sebagai referensi master. Saat mahasiswa membuat aktivitas dari template ini, data disalin sebagai data pribadi mahasiswa. Menghapus atau menonaktifkan template ini tidak akan menghapus atau mengubah aktivitas yang sudah dibuat oleh mahasiswa.
            </div>
        </div>

        <div class="editor-actions" style="margin-top: 30px; padding-bottom: 0;">
            <div style="display: flex; gap: 10px;">
                <a class="button button--primary" href="{{ route('admin.action-templates.edit', $template) }}">
                    Edit Template
                </a>

                @if ($template->is_active)
                    <form method="POST" action="{{ route('admin.action-templates.destroy', $template) }}"
                          data-confirm-action
                          data-confirm-title="Nonaktifkan Template?"
                          data-confirm-message="Template '{{ $template->title }}' tidak akan lagi ditampilkan sebagai referensi baru untuk mahasiswa. Aktivitas mahasiswa yang sudah ada tetap aman."
                          data-confirm-submit="Nonaktifkan">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="deactivate">
                        <button class="button button--outline" type="submit">
                            Nonaktifkan Template
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.action-templates.toggle-status', $template) }}">
                        @csrf
                        <button class="button button--outline" type="submit">
                            Aktifkan Template
                        </button>
                    </form>
                @endif
            </div>

            <div>
                <form method="POST" action="{{ route('admin.action-templates.destroy', $template) }}"
                      data-confirm-action
                      data-confirm-title="Hapus Template Secara Permanen?"
                      data-confirm-message="Template '{{ $template->title }}' akan dihapus dari master template. Seluruh aktivitas mahasiswa yang pernah dibuat dari template ini tetap aman."
                      data-confirm-submit="Hapus Permanen">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="action" value="delete">
                    <button class="button button--danger-outline" type="submit">
                        Hapus Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="dialog-title">
    <h2 id="dialog-title" data-dialog-title>Konfirmasi</h2>
    <p data-dialog-message>Apakah Anda yakin?</p>
    <div class="dialog-actions">
        <button class="button button--outline" type="button" data-dialog-cancel>Batal</button>
        <button class="button button--primary" type="button" data-dialog-submit>Lanjutkan</button>
    </div>
</dialog>
@endsection
