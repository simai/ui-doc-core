@php
    $items = $sub ?? $page->configurator->getItems();
    $level = $level ?? 0;
    $isSub = $isSub ?? false;
    $prefix = $prefix ?? '';
@endphp

<ul class="nav-menu menu-level-{{ $level }}">
    @foreach ($items as $slug => $item)
        @php

            $title = $item['current']['title'] ?? null;
            $menu = $item['current']['menu'] ?? [];
            $hasSub = !empty($item['pages']);
        @endphp


        <li class="{{$page->isActiveParent($slug) ? 'active' : ''}}">
            @if ($title)
                <button onclick="toggleNav(this)" class="nav-button" type="button">{{$title}}</button>
            @endif

            @if (!empty($menu))
                <ul>
                    @foreach ($menu as $key => $label)
                        @php
                            $fullPath = ($slug === $key) ? $isSub ? $prefix . '/' . $key : $key : trim($slug . '/' . $key, '/');
                            $url = '/' . $page->language . '/' . $fullPath;
                        @endphp

                        <li>
                            <a href="{{ $page->url($url) }}"
                               class="lvl{{ $level }} {{ $page->isActive($url) ? 'active text-blue-500' : '' }}">
                                {{ $label }}
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
    'prefix' => $slug]
    )
            @endif
        </li>
    @endforeach
</ul>
