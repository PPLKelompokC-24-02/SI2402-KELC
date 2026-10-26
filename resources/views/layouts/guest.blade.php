<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F8F7F2">
    <meta name="description" content="Kenali diri, eksplorasi profesi, dan rencanakan langkah pengembangan kariermu bersama Career Compass. Untuk mahasiswa S1 Sistem Informasi Telkom University Kampus Jakarta.">
    <title>@yield('title', 'Career Compass — Temukan arah kariermu')</title>
    <link rel="icon" href="{{ asset('images/compass.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#auth-card">Langsung ke formulir</a>
    <header class="site-header">
        <nav class="header-inner" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('landing') }}" aria-label="Career Compass — Beranda">
                <span class="brand-mark"><x-compass /></span>
                <span>career compass</span>
            </a>
        </nav>
    </header>
    <main class="landing-layout">
        <section class="hero" aria-labelledby="hero-title">
            <p class="eyebrow"><x-arrow data-direction="diagonal" /> <span>KENALI DIRI. TEMUKAN ARAH.</span></p>
            <h1 id="hero-title">Langkah berikutnya,<br>lebih <em>terarah.</em></h1>
            <p class="hero-description">Karier yang tepat dimulai dari mengenal diri. Eksplorasi profesi, pahami kompetensi yang dibutuhkan, dan rencanakan langkah pengembangan kariermu bersama Career Compass.</p>
            <div class="hero-action">
                <a class="hero-cta" href="#auth-card" data-focus-auth>Temukan arah kariermu <x-arrow /></a>
                <span class="hero-microcopy">Mulai dari langkah kecil.</span>
            </div>
        </section>
        <section class="auth-card" id="auth-card" aria-labelledby="auth-title" tabindex="-1">
            <div class="card-brand"><x-compass /><span>CAREER COMPASS</span></div>
            @yield('auth-content')
        </section>
    </main>
</body>
</html>
