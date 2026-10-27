@props(['name'])
<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.6', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('home') <path d="m3 10 9-7 9 7v10H3V10Z" /><path d="M9 20v-7h6v7" /> @break
        @case('user') <circle cx="12" cy="8" r="3.5" /><path d="M5 21v-2a7 7 0 0 1 14 0v2" /> @break
        @case('users') <circle cx="9" cy="8" r="3" /><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 5a3 3 0 0 1 0 6m3 10v-2a6 6 0 0 0-3-5" /> @break
        @case('logout') <path d="M10 4H4v16h6m3-12 4 4-4 4m-5-4h13" /> @break
        @case('compass') <circle cx="12" cy="12" r="9" /><path d="m16 8-2.5 5.5L8 16l2.5-5.5L16 8Z" /> @break
        @case('briefcase') <rect x="3" y="7" width="18" height="14" rx="2" /><path d="M8 7V4h8v3M3 12a22 22 0 0 0 18 0m-9 0v3" /> @break
        @case('book') <path d="M12 5c-3-2-6-2-9-1v15c3-1 6-1 9 1 3-2 6-2 9-1V4c-3-1-6-1-9 1v15" /> @break
        @case('assessment') <rect x="5" y="4" width="14" height="17" rx="2" /><path d="M9 4V2h6v2M9 9h6m-6 4h6m-6 4h4" /> @break
        @case('plan') <rect x="3" y="4" width="18" height="17" rx="2" /><path d="m7 9 1 1 2-2m3 1h4m-10 7 1 1 2-2m3 1h4" /> @break
        @case('calendar') <rect x="3" y="5" width="18" height="16" rx="2" /><path d="M7 3v4m10-4v4M3 11h18m-14 4h2m4 0h2" /> @break
        @case('document') <path d="M5 3h9l5 5v13H5V3Zm9 0v5h5M9 12h6m-6 4h6" /> @break
        @case('progress') <circle cx="12" cy="12" r="9" /><path d="m8 12 3 3 5-6" /> @break
        @case('monitor') <rect x="3" y="4" width="18" height="13" rx="2" /><path d="M8 21h8m-4-4v4m-5-10 3-3 3 3 4-4" /> @break
        @case('inbox') <path d="m3 12 3-8h12l3 8v8H3v-8Zm0 0h5l2 3h4l2-3h5" /> @break
        @case('publish') <path d="M4 15v6h16v-6M12 16V3m-5 5 5-5 5 5" /> @break
        @case('history') <path d="M3 11a9 9 0 1 1 2 7M3 4v7h7m2-4v6l4 2" /> @break
        @case('menu') <path d="M4 6h16M4 12h16M4 18h10" /> @break
        @case('close') <path d="m6 6 12 12M6 18 18 6" /> @break
        @case('chevron') <path d="m8 10 4 4 4-4" /> @break
    @endswitch
</svg>
