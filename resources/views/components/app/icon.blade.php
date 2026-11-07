@props(['name'])
<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.6', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('data') <rect x="3" y="3" width="18" height="18" rx="3" /><path d="M7 16v-4m5 4V8m5 8v-6" /> @break
        @case('code') <rect x="2" y="4" width="20" height="16" rx="3" /><path d="m8 9-3 3 3 3m8-6 3 3-3 3m-5 1 2-8" /> @break
        @case('design') <path d="M4 20h4L21 7l-4-4L4 16v4Zm10-14 4 4M3 4h5M3 8h3" /> @break
        @case('check') <path d="m5 12 4 4L19 6" /> @break
        @case('back') <path d="m10 5-7 7 7 7M3 12h18" /> @break
        @case('image') <rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8" cy="8" r="1.5" /><path d="m3 17 6-6 4 4 3-3 5 5" /> @break
        @case('plus') <path d="M12 5v14M5 12h14" /> @break
        @case('search') <circle cx="10.5" cy="10.5" r="6.5" /><path d="m16 16 5 5" /> @break
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
        @case('heart') <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /> @break
        @case('heart-filled') <path fill="currentColor" stroke="currentColor" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /> @break
        @case('trash') <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /> @break
        @case('edit') <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /> @break
        @case('eye') <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /> @break
        @case('mail') <rect width="20" height="16" x="2" y="4" rx="2" /><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" /> @break
        @case('phone') <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" /> @break
        @case('map-pin') <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /> @break
        @case('external') <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3" /> @break
        @case('award') <circle cx="12" cy="8" r="6" /><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11" /> @break
        @case('download') <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" /> @break
    @endswitch
</svg>
