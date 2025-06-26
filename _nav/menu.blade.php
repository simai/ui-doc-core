@php
    $locale = $page->locale();
    $level = $level ?? 0;
    $menuTree = $menuTree ?? $page->configurator->getMenu($locale);
    $paths = $page->configurator->paths;
@endphp


<ul class="sf-nav-menu-wrap menu-level-{{ $level }}">
    @foreach ($menuTree as $path => $node)
        @php
            $hasPage = in_array($path, $paths);
            $activeParent = $page->isActiveParent($node);
            $activeItem = $page->getPath() === $path;
        @endphp
        <li class="sf-nav-menu-element {{$activeParent && !$activeItem ? 'active' : ''}}">
            @if (!empty($node['children']))
                @if($hasPage)
                    <div class="sf-nav-item flex {{ $activeItem   ? 'visited' : '' }} items-center justify-between">
                        <button class="sf-nav-toggle_button flex items-center" onclick="toggleNav(this)" type="button">
                            <i class="sf-icon">keyboard_arrow_down</i>
                        </button>
                        <a href="{{$path}}" class="sf-nav-button flex" type="button">
                            <span class="sf-nav-title">{{ $node['title'] }}</span>
                        </a>
                    </div>
                @else
                    <button onclick="toggleNav(this)"
                            class="sf-nav-button sf-nav-item flex items-center" type="button">
                              <span class="sf-nav-toggle_button flex items-center">
                                            <i class="sf-icon">keyboard_arrow_down</i>
                                        </span>
                        <span class="sf-nav-title">{{ $node['title'] }}</span>
                    </button>
                @endif
            @else
                <a href="{{ $path }}"
                   class="sf-nav-menu-element--link sf-nav-item items-center flex sf-nav-menu--lvl{{ $level }} {{ $page->isActive($path) ? 'active' : '' }}">
                    <span class="sf-nav-title">{{ $node['title'] }}</span>
                </a>
            @endif
            @if (!empty($node['children']))
                @include('_core._nav.menu', ['menuTree' => $node['children'], 'level' => $level + 1,])
            @endif
        </li>
    @endforeach
</ul>

