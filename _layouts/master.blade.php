<!DOCTYPE html>
@php
    $locale = $page->locale();
        $page->configurator->setLocale($locale);
        $localesItems = $page->locales->toArray();
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
        <!-- Insert analytics code here -->
    @endif
    <script>
        window.sfPath = 'https://cdn.jsdelivr.net/gh/simai/ui@main/distr/';
    </script>
    <script src="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/js/core.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/css/core.css"/>

    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    <script>
        window.getCookie = function (name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/css/core.css">
    <script src="https://cdn.jsdelivr.net/gh/simai/ui@main/distr/core/js/core.js"></script>



        <style>
            header{
                border-bottom: 1px solid var(--sf-outline-variant);
            }
            aside{
                border-right: 1px solid var(--sf-outline-variant);
            }
            aside ul{
                list-style-type: none;
            }
            aside ul li a, aside ul button {
                font-weight: var(--sf-text--font-weight);
                padding: var(--sf-space-1\/2) var(--sf-space-1);
                font-size: var(--sf-text--size-1);
                color: var(--sf-on-surface);
                line-height: var(--sf-text--height-1);
                display: flex;
            }
            aside .nav-button{
                font-weight: inherit;
                font-size: inherit;
                width: 100%;
                display: inline-flex;
                justify-content: space-between;
            }

            aside .nav-button .sf-icon{
                height: var(--sf-c6); //32px;
            }

            #docsearch-input{
                min-width: var(--sf-f7);
            }

            .sf-menu li{
                display: inline;
                font-size: var(--sf-text--size-1);
                padding-left: var(--sf-b2);
                padding-right: var(--sf-b2);
            }
            .sf-menu li a{
                color: var(--sf-on-surface);
                font-weight: var(--sf-text--font-weight-5);
            }

            .sf-button.sf-button--nav-switch{
                --sf-button--text-size: var(--sf-text--size-3);
            }

        </style>
</head>

<body class="theme-light flex flex-col justify-between min-h-screen bg-gray-100 text-gray-800 leading-normal">
<header class="w-full flex p-top-1 p-bottom-1 md:p-top-1 md:p-bottom-1 " role="banner">
    <div style="display: none">test123</div>
    <div class="container flex items-center  mx-auto px-4 lg:px-8">
        <div class="flex items-center">
            <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                <img class="h-8 md:h-10 mr-3" src="{{mix('/img/logo.svg', 'assets/build')}}"
                     alt="{{ $page->siteName }} logo"/>

                <h1 class="text-lg md:text-2xl text-blue-900 font-semibold hover:text-blue-600 my-0 pr-4">Simai<!--{{ $page->siteName }}--></h1>
            </a>
            <ul class = "sf-menu">
                    <li>
                        <a href="#"> Концепция </a>
                    </li>
                    <li>
                        <a href="#"> Ядро </a>
                    </li>
                    <li>
                        <a href="#"> Утилиты </a>
                    </li>
                    <li>
                        <a href="#"> Компоненты </a>
                    </li>
                    <li>
                        <a href="#"> Смарт-компоненты </a>
                    </li>
                </ul>
        </div>

        <div class="flex flex-1 justify-end items-center text-right md:pl-10">
            @include('_core._nav.search-input')
        </div>
        <div>
            <button class = "sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch">
                <i class = "sf-icon">Language</i>
            </button>
            <button class = "sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch">
                <i class = "sf-icon">Dark_Mode</i>
            </button>
            <form id="locale-switcher" style="margin-bottom: 1em; display: none;">
                <label for="locale">Language: </label>
                <select name="locale" id="locale">
                    @foreach($localesItems as $code => $label)
                        <option value="{{ $code }}" @if($code === $locale) selected @endif>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @yield('nav-toggle')
</header>
<div class="w-full flex-auto ">
    <div class="flex mx-auto px-6 md:px-8 container">
        <aside>
            <nav id="js-nav-menu" class="nav-menu hidden lg:block">
                @include('_core._nav.menu', ['items' => $page->navigation])
            </nav>
        </aside>
        <main role="main" class="w-full lg:w-3/5 break-words pb-16 lg:pl-4">
            @yield('body')
        </main>
    </div>
</div>
<script src="{{ mix('js/main.js', 'assets/build') }}"></script>
<script>
    document.getElementById('locale').addEventListener('change', function () {
        const newLocale = this.value;
        document.cookie = `locale=${newLocale}; path=/; max-age=31536000`; // 1 year
        const currentPath = window.location.pathname.split('/');
        const currentLocale = '{{ $locale }}';
        window.location.href = currentPath.map((segment, index) =>
            segment === currentLocale ? newLocale : segment
        ).join('/');
    });
</script>
@stack('scripts')

</body>
</html>
