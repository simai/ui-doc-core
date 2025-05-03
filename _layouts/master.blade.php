<!DOCTYPE html>
@php
    $currentLocale = $page->configurator->locale;
    $locales = $page->locales->toArray();
@endphp


<html lang="{{$currentLocale}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

        <meta property="og:site_name" content="{{ $page->siteName }}"/>
        <meta property="og:title" content="{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
        <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}"/>
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:image" content="/assets/img/logo.png"/>
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
            <!-- Insert analytics code here -->
        @endif
        <script>
            window.sfPath = 'https://cdn.jsdelivr.net/gh/simai/ui@main/distr/';
        </script>
        <script src="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/js/core.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/css/core.css"/>

        <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">

        @if ($page->docsearchApiKey && $page->docsearchIndexName)
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css" />
        @endif

    </head>

    <body class="flex flex-col justify-between min-h-screen bg-gray-100 text-gray-800 leading-normal">
        <header class="w-full flex p-top-1 p-bottom-1 md:p-top-1 md:p-bottom-1 " role="banner">
            <div class="container flex items-center  mx-auto px-4 lg:px-8">
                <div class="flex items-center">
                    <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                        <img class="h-8 md:h-10 mr-3" src="{{mix('/img/logo.svg', 'assets/build')}}" alt="{{ $page->siteName }} logo" />

                        <h1 class="text-lg md:text-2xl text-blue-900 font-semibold hover:text-blue-600 my-0 pr-4">{{ $page->siteName }}</h1>
                    </a>
                </div>

                <div class="flex flex-1 justify-end items-center text-right md:pl-10">
                    @if ($page->docsearchApiKey && $page->docsearchIndexName)
                        @include('_nav.search-input')
                    @endif
                </div>
                <div>
                    <form id="locale-switcher" style="margin-bottom: 1em;">
                        <label for="locale">Language: </label>
                        <select name="locale" id="locale">
                            @foreach($locales as $code => $label)
                                <option value="{{ $code }}" @if($code === $currentLocale) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @yield('nav-toggle')
        </header>
        <main role="main" class="w-full flex-auto">
            @yield('body')
        </main>

        <script src="{{ mix('js/main.js', 'assets/build') }}"></script>
        <script>
            document.getElementById('locale').addEventListener('change', function () {
                const newLocale = this.value;
                document.cookie = `locale=${newLocale}; path=/; max-age=31536000`; // 1 year
                const currentPath = window.location.pathname.split('/');
                const currentLocale = '{{ $currentLocale }}';
                window.location.href = currentPath.map((segment, index) =>
                    segment === currentLocale ? newLocale : segment
                ).join('/');
            });
        </script>
        @stack('scripts')

    </body>
</html>
