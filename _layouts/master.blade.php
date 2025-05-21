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

    @include('_core._layouts.core')

    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    <script>
        window.getCookie = function (name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>




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

            main{
                padding: var(--sf-space-4);
            }

            #docsearch-input{
                min-width: var(--sf-f7);
            }

            .sf-menu{
                display: inline-flex;
            }

            .sf-menu li{
                display: inline-flex;
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

        /* Стили для элементов меню */
        .sf-dropdown-menu-item {
            padding: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            color: var(--sf-dropdown--color);
            border-bottom: var(--sf-a1) solid var(--sf-surface-container-active);
        }

        /* Изменение цвета при наведении на элемент меню */
        .sf-dropdown-menu-item:hover {
            background-color: #f1f1f1;
        }

        /* Класс для отображения меню */
        .sf-dropdown-menu.show {
            display: block;
        }



        /*переключение языков */
        .sf-language-switch--language-item input[type='radio']{
            display: none; 
        }
        .sf-language-switch--language-item
        {
            padding: var(--sf-space-1\/3) var(--sf-space-1);
            line-height: var(--sf-text--height-1);
        }

        .sf-language-switch--language-panel{
            --sf-language-switch--language-panel-display: none; 
            position: absolute;
            background-color: var(--sf-surface-2);
            top: 150%;
            left: 0;
            width: 100%;
            overflow-y: auto;
            left: 50%;
            transform: translateX(-50%);
            border-radius: var(--sf-radius-1);
            display: var(--sf-language-switch--language-panel-display); 
            z-index: 1;
            margin: 0;
            padding: 0;
            list-style: none;
            border-top: none;
            max-width: max-content;
            min-width: max-content;
        }

        .sf-language-switch--language-panel.sf-language-switch--language-panel-show{
            --sf-language-switch--language-panel-display: flex;
        }

        .sf-language-switch--language-item .sf-language-switch--check
        {
            display: none;
        }
        </style>
</head>

<body class="theme-light flex flex-col justify-between min-h-screen leading-normal">
<header class="w-full flex p-top-1 p-bottom-1 md:p-top-1 md:p-bottom-1 " role="banner">
    <div class="container flex items-center  mx-auto px-4 lg:px-8">
        <div class="flex items-center">
            <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                <img class="mr-3" src="{{mix('/img/icon_and_text_logo.svg', 'assets/build')}}"
                     alt="{{ $page->siteName }} logo"/>

                <!--<h1 class="text-lg md:text-2xl text-blue-900 font-semibold hover:text-blue-600 my-0 pr-4">{{ $page->siteName }}</h1>-->
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
            <!--<button class = "sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch" id = "lang_switch">
                <i class = "sf-icon">Language</i>
            </button>-->
            <div class = "sf-language-switch sf-language-switch--container"style="position: relative; max-width: 56px; display: inline-flex">
                <button class="sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch sf-language-switch--button" id="lang_switch">
                    <i class="sf-icon">Language</i>
                </button>
                <div class="sf-language-switch--language-panel" id="language_panel">
                    <ul class="sf-language-switch--language-list">
                        <label><li class="sf-language-switch--language-item"><span>English</span> <input type="radio" name="laguage_switch_radio" value="en"> <i class="sf-icon sf-icon-light sf-language-switch--check">check</i></li></label>
                        <label><li class="sf-language-switch--language-item"><span>Русский</span> <input type="radio" name="laguage_switch_radio" value="ru"> <i class="sf-icon sf-icon-light sf-language-switch--check">check</i></li></label>
                        <label><li class="sf-language-switch--language-item"><span>Deutsch</span> <input type="radio" name="laguage_switch_radio" value="de"> <i class="sf-icon sf-icon-light sf-language-switch--check">check</i></li></label>
                        <label><li class="sf-language-switch--language-item"><span>Español</span> <input type="radio" name="laguage_switch_radio" value="es"> <i class="sf-icon sf-icon-light sf-language-switch--check">check</i></li></label>
                    </ul>
                </div>
                
            </div>

            <button class = "sf-button sf-theme-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch" id = "theme-toggle">
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
    //event on click on language switch element
    document.getElementById('lang_switch').addEventListener('click', function(){

        const loc_switch = '{{ $locale }}';

        const language_switch_panel = this.parentElement.querySelector('.sf-language-switch--language-panel');
        if(language_switch_panel.classList.contains("sf-language-switch--language-panel-show"))
            language_switch_panel.classList.remove("sf-language-switch--language-panel-show");
        else
            language_switch_panel.classList.add("sf-language-switch--language-panel-show");
    });

    const language_item = document.querySelectorAll('.sf-language-switch--language-item');
    console.log(language_item);
    
    [...language_item].forEach(element => {
        element.querySelector("input[type='radio']").addEventListener('change', function(e){
            if(e.currentTarget.checked){
                //e.currentTarget.parentElement.querySelector('.sf-language-switch--check').classList.remove("sf-language-switch--check");
                let optionExists = Array.from(document.getElementById('locale').options).some(option => option.value === e.currentTarget.value);
                if(optionExists){
                    document.getElementById('locale').value = e.currentTarget.value;
                    document.getElementById('locale').dispatchEvent(new Event("change", { bubbles: true }));
                }
                
            }
        });
        if(element.querySelector("input[type='radio']").value == '{{$locale}}'){
            console.log(element.querySelector(".sf-language-switch--check").classList.remove('sf-language-switch--check'));
        }
                
    });


    document.getElementById('locale').addEventListener('change', function () {
        const newLocale = this.value;
        document.cookie = `locale=${newLocale}; path=/; max-age=31536000`; // 1 year
        const currentPath = window.location.pathname.split('/');
        const currentLocale = '{{ $locale }}';
        window.location.href = currentPath.map((segment, index) =>
            segment === currentLocale ? newLocale : segment
        ).join('/');
    });

    window.addEventListener('click', function(e){   
        console.log(e.target);
    if (!e.target.querySelector('.sf-language-switch--language-panel') && !e.target.closest('button.sf-language-switch--button')){
        document.querySelector('.sf-language-switch--language-panel').classList.remove("sf-language-switch--language-panel-show");
        // Clicked in box
    } else{
        // Clicked outside the box
        console.log('Clicked outside the box');
    }   
    });



    /*document.getElementById('theme-toggle').addEventListener('click', () => {
        const html = document.body;
        const isDark = html.classList.contains('theme-dark');

        // Переключаем тему
        html.classList.remove(isDark ? 'theme-dark' : 'theme-light');
        html.classList.add(isDark ? 'theme-light' : 'theme-dark');

        // Сохраняем в localStorage
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });*/

</script>
@stack('scripts')

</body>
</html>
