@php
    $items = $sub ?? $page->configurator->getItems($page->locale());
    $level = $level ?? 0;
    $isSub = $isSub ?? false;
    $prefix = $prefix ?? '';
@endphp

<ul class="sf-nav-menu menu-level-{{ $level }}">
    @foreach ($items as $slug => $item)
        @php
            $title = $item['current']['title'] ?? null;
            $menu = $item['current']['menu'] ?? [];
            $hasSub = !empty($item['pages']);
            $isLocaleRoot = ($slug == $page->locale());
            $buttonTitle = $isLocaleRoot ? 'Основы' : $title;
            $isActive = $page->isActiveParent(trim($slug)) && count($items) > 1;
        @endphp

        <li class="sf-nav-menu-element {{ $isActive ? 'active' : '' }}">
            @if ($buttonTitle)
                <button onclick="toggleNav(this)" class="sf-nav-button flex content-main-between" type="button">
                    <span>{{ $buttonTitle }}</span>
                    <i class="sf-icon">keyboard_arrow_down</i>
                </button>
            @endif
            @if (!empty($menu))
                <ul>
                    @foreach ($menu as $key => $label)
                        @php
                            $fullPath = ($slug === $key) ? ($isSub ? $prefix . '/' . $key : $key) : trim($slug . '/' . $key, '/');
                            $url = ($slug === $page->language ? '' : '/' . $page->language) . '/' . $fullPath;
                        @endphp
                        <li class="sf-nav-menu-element">
                            <a href="{{ $page->url($url) }}"
                               class="sf-nav-menu-element--link sf-nav-menu--lvl{{ $level }} {{ $page->isActive($url) ? 'active text-blue-500' : '' }}">
                                <span>{{ $label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($hasSub)
                @include('_core._nav.menu', [
                    'sub' => $item['pages'],
                    'level' => $level + 1,
                    'isSub' => true,
                    'prefix' => $slug
                ])
            @endif
        </li>
    @endforeach
</ul>
