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
        @endphp


        <li class="sf-nav-menu-element {{$page->isActiveParent($slug) ? 'active' : ''}}">
            @if ($title && $slug !== $page->locale())
                <button onclick="toggleNav(this)" class="sf-nav-button" type="button">
                    <i class = "sf-icon">
                        @php
                            if($page->isActiveParent($slug))
                                echo "keyboard_arrow_up";
                            else
                                echo "keyboard_arrow_down";
                        @endphp
                    </i>    
                    {{$title}}  
                </button>
            @endif
             @if ($slug == $page->locale())
                <button onclick="toggleNav(this)" class="sf-nav-button" type="button">
                    <i class = "sf-icon">
                        @php
                            if($page->isActiveParent($slug))
                                echo "keyboard_arrow_up";
                            else
                                echo "keyboard_arrow_down";
                        @endphp
                    </i>
                    Основы 
                </button>
            @endif

            @if (!empty($menu))
                <ul>
                    @foreach ($menu as $key => $label)
                        @php
                            $fullPath = ($slug === $key) ? $isSub ? $prefix . '/' . $key : $key : trim($slug . '/' . $key, '/');
                            $url = ($slug === $page->language ? '' : '/' . $page->language ). '/' . $fullPath;
                        @endphp

                        <li class = "sf-nav-menu-element">
                            <a href="{{ $page->url($url) }}"
                               class="sf-nav-menu-element--link sf-nav-menu--lvl{{ $level }} {{ $page->isActive($url) ? 'active text-blue-500' : '' }}">
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
