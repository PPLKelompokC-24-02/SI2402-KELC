<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F8F7F2">
    <title>@yield('title', 'Beranda — Career Compass')</title>
    <link rel="icon" href="{{ asset('images/compass.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</head>
<body class="app-shell">
    <a class="skip-link" href="#main-content">Langsung ke konten</a>
    <x-app.topbar :user="$user" :role-label="$roleLabel" :initials="$initials" />
    <div class="sidebar-backdrop" data-sidebar-backdrop hidden></div>
    <x-app.sidebar :user="$user" :role-label="$roleLabel" :initials="$initials" :navigation="$navigation" />
    <main class="app-main" id="main-content" tabindex="-1">
        @yield('content')
    </main>
</body>
</html>
