@props(['user', 'roleLabel', 'initials'])
<header class="app-topbar">
    <a class="app-brand" href="{{ route('home') }}" aria-label="Career Compass — Beranda">
        <span class="app-brand-mark"><x-compass /></span>
        <span>career compass</span>
    </a>
    <button class="icon-button sidebar-toggle" type="button" data-sidebar-toggle aria-controls="app-sidebar" aria-expanded="true" aria-label="Tutup sidebar">
        <x-app.icon name="menu" />
    </button>
    <details class="profile-menu" data-profile-menu>
        <summary class="profile-trigger">
            <span class="user-avatar" aria-hidden="true">{{ $initials }}</span>
            <span class="topbar-user-copy"><span class="topbar-user-name">{{ $user->name }}</span><span class="topbar-user-role">{{ $roleLabel }}</span></span>
            <x-app.icon name="chevron" class="profile-chevron" />
            <span class="sr-only">Buka menu akun</span>
        </summary>
        <div class="profile-dropdown">
            <p class="dropdown-name">{{ $user->name }}</p>
            <p class="dropdown-email">{{ $user->email }}</p>
            <span class="role-label">{{ $roleLabel }}</span>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-logout" type="submit"><x-app.icon name="logout" /> Logout</button>
            </form>
        </div>
    </details>
</header>
