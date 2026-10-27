@props(['user', 'roleLabel', 'initials', 'navigation'])
<aside class="app-sidebar" id="app-sidebar" aria-label="Navigasi aplikasi" tabindex="-1">
    <div class="sidebar-scroll">
        <div class="drawer-heading"><span>Menu navigasi</span><button class="icon-button" type="button" data-sidebar-close aria-label="Tutup sidebar"><x-app.icon name="close" /></button></div>
        <x-app.user-profile :user="$user" :role-label="$roleLabel" :initials="$initials" />
        <nav class="sidebar-nav" aria-label="Menu {{ $roleLabel }}">
            @foreach ($navigation as $section)
                <section class="nav-section {{ $loop->last ? 'nav-section--account' : '' }}" aria-labelledby="nav-section-{{ $loop->index }}">
                    <h2 id="nav-section-{{ $loop->index }}" class="nav-section-title">{{ $section['title'] }}</h2>
                    <ul class="nav-list">
                        @foreach ($section['items'] as $item)
                            <li>
                                @if ($item['url'] && ($item['method'] ?? 'GET') === 'POST')
                                    <form action="{{ $item['url'] }}" method="POST">
                                        @csrf
                                        <button class="nav-link nav-link--logout" type="submit"><x-app.icon :name="$item['icon']" /><span>{{ $item['label'] }}</span></button>
                                    </form>
                                @elseif ($item['url'])
                                    <a class="nav-link {{ $item['active'] ? 'is-active' : '' }}" href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif>
                                        <x-app.icon :name="$item['icon']" /><span>{{ $item['label'] }}</span>
                                    </a>
                                @else
                                    <span class="nav-link nav-link--disabled" aria-disabled="true">
                                        <x-app.icon :name="$item['icon']" /><span class="nav-link-copy"><span>{{ $item['label'] }}</span><span class="nav-status">Segera Hadir</span></span>
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </nav>
    </div>
</aside>
