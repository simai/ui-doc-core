<!DOCTYPE html>
@php
        $locale = $page->locale();
        $page->configurator->setLocale($locale);
@endphp


<html lang="{{$locale}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

    <meta property="og:site_name" content="{{ $page->siteName }}"/>
    <meta property="og:title" content="{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
    <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}"/>
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:image" content="{{mix('/img/logo.svg', 'assets/build')}}"/>
    <meta property="og:type" content="website"/>

    <meta name="twitter:image:alt" content="{{ $page->siteName }}">
    <meta name="twitter:card" content="summary_large_image">

    @if ($page->docsearchApiKey && $page->docsearchIndexName)
        <meta name="generator" content="tighten_jigsaw_doc">
    @endif

    <title>{{ $page->siteName }}{{ $page->title ? ' | ' . $page->title : '' }}</title>

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="/favicon.ico">

    @stack('meta')

    @if ($page->production)
        <script src="{{ mix('js/turbo.js', 'assets/build') }}"></script>
    @endif
    @include('_core._layouts.core')

    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    <script>
        window.getCookie = function (name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
    <script data-turbo-permanent src="{{ mix('js/main.js', 'assets/build') }}"></script>

</head>

<body class="theme-light flex flex-col justify-between min-h-screen leading-normal max-container-6">
<header class="w-full flex p-top-2 p-bottom-2 md:p-top-2 md:p-bottom-2 container p-left-0 p-right-0" role="banner">
    @include('_core._nav.top-menu')

    <div class="flex flex-1 justify-end items-center text-right md:pl-10 gap-x-1">
        @include('_core._nav.search-input')

        @include('_core._components.language')

        <button class="sf-button sf-theme-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch"
                id="theme-toggle">
            <i class="sf-icon">Dark_Mode</i>
        </button>


    </div>
</header>
<div class="w-full flex flex-auto justify-center container p-left-0 p-right-0">
    <!--sf-container sf-container-main-->
    <aside class="sf-nav-menu sf-nav-menu--right">
        <nav id="js_nav_left" class="nav-menu lg:block">
            @include('_core._nav.menu', ['items' => $page->navigation])
        </nav>
    </aside>
    <main role="main" class="container--main break-words ">
        @yield('body')
    </main>
    <aside class="sf-nav-menu side-menu">
        <nav id="js_nav_right" class="nav-menu lg:block">
            @include('_core._nav.side-menu', ['items' => $page->navigation])
        </nav>
    </aside>
</div>

@stack('scripts')

</body>
</html>
