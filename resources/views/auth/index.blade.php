@extends('layouts.guest')

@section('title', $register ? 'Daftar — Career Compass' : 'Selamat datang — Career Compass')

@section('auth-content')
        <h2 id="auth-title">{{ $register ? 'Mulai langkahmu' : 'Selamat datang' }}</h2>
        <p class="card-subtitle">{{ $register ? 'Buat akun untuk menemukan arahmu.' : 'Masuk untuk melanjutkan' }}</p>

        @if (session('status'))
            <p class="auth-notice auth-notice--success" role="status">{{ session('status') }}</p>
        @endif

        <form class="auth-form" action="{{ $register ? route('register.store') : route('login.store') }}" method="POST" data-auth-form data-mode="{{ $register ? 'register' : 'login' }}">
            @csrf
            <div class="auth-notice auth-notice--error" data-form-error role="alert" tabindex="-1" hidden></div>

            @if ($register)
                <x-auth-field name="name" label="Nama lengkap" placeholder="Nama lengkapmu" autocomplete="name" maxlength="255" />
            @endif

            <x-auth-field name="email" label="Alamat email" type="email" placeholder="nama@email.com" autocomplete="email" maxlength="255" inputmode="email" autocapitalize="none" spellcheck="false" />

            @if ($register)
                <x-auth-field name="password" label="Kata sandi" type="password" placeholder="Buat kata sandi" autocomplete="new-password" minlength="8" maxlength="72" hint="Minimal 8 karakter, dengan huruf dan angka." />
                <x-auth-field name="password_confirmation" label="Konfirmasi kata sandi" type="password" placeholder="Ulangi kata sandi" autocomplete="new-password" minlength="8" maxlength="72" />
            @else
                <x-auth-field name="password" label="Kata sandi" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" maxlength="72" />
                <div class="form-options">
                    <label class="remember-label" for="remember"><input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))> <span>Ingat saya</span></label>
                </div>
            @endif

            <button class="primary-button" type="submit" data-submit-button>
                <span data-submit-label>{{ $register ? 'Buat akun' : 'Masuk ke akun' }}</span>
                <span class="button-spinner" aria-hidden="true"></span>
                <x-arrow data-direction="diagonal" class="button-arrow" />
            </button>
            <span class="sr-only" data-form-status role="status" aria-live="polite"></span>
        </form>

        <p class="card-footer">
            {{ $register ? 'Sudah punya akun?' : 'Belum punya akun?' }}
            <a href="{{ $register ? route('login').'#auth-card' : route('register').'#auth-card' }}">{{ $register ? 'Masuk sekarang' : 'Daftar sekarang' }}</a>
        </p>
@endsection
