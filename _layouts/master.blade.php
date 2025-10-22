<!DOCTYPE html>
@php
    $locale = $page->locale();
    $page->configurator->setLocale($locale);
@endphp


<html lang="{{$locale}}">

@include('_core._layouts.head')

<body class="theme-light flex flex-col justify-between min-h-screen leading-normal max-container-6">

@include('_core._layouts.header')

<div class="w-full flex flex-auto justify-center container p-left-1 p-right-1">
    <!--sf-container sf-container-main-->
    <aside id="main_menu" class="sf-nav-menu w-full sf-nav-menu--left">
        <div class="sf-nav-wrap aside-wrap scroll-border">
            <nav id="js_nav_left" class="nav-menu-left lg:block">
                @include('_core._nav.menu', ['items' => $page->navigation])
            </nav>
        </div>
    </aside>
    <main role="main" class="container--main break-words ">
        @yield('body')
    </main>
    <aside id="side_menu" class="sf-nav-menu sf-nav-menu--right side-menu">
        <div class="aside-wrap w-full scroll-border">
            <nav id="js_nav_right" class="nav-menu-right lg:block">
                @include('_core._nav.side-menu', ['items' => $page->navigation])
            </nav>
        </div>
    </aside>
</div>
[!Fab](size=2 type=primary)
@stack('scripts')
<button onclick="navOpen()" id="sf_segment"
        class="sf-button sf-button-segment sf-button-size-1 sf-icon-button-size-1 sf-button--on-surface sf-button-type-link side-menu-instrument">
    <i class="sf-icon">segment</i>
</button>

</body>
</html>
