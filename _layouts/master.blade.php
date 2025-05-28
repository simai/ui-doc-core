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
                box-sizing: border-box;
                max-height: var(--sf-e0);
            }
            /*.sf-nav-menu--right{
                border-right: 1px solid var(--sf-outline-variant);
            }*/
            .sf-nav-menu{
                flex: 0 0 20vw;
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
            aside .sf-nav-button{
                font-weight: inherit;
                font-size: inherit;
                width: 100%;
                display: inline-flex;
                /*justify-content: space-between;*/
            }

            aside .sf-nav-button .sf-icon{
                font-size: var(--sf-text--height-1);
                height: var(--sf-с0);
            }

            .sf-nav-menu--lvl0, .menu-level-1 > li > .sf-nav-button{
                padding-left: var(--sf-space-3);
            }

            .sf-nav-menu--lvl1{
                padding-left: var(--sf-space-4);
            }

            main{
                padding: var(--sf-space-4);
                border-right: 1px solid var(--sf-outline-variant);
                border-left: 1px solid var(--sf-outline-variant);
                //flex: 0 0 40vw;
                //max-width: calc(var(--sf-container-8--size-max) / 2);
                max-width: 50%;
            }

            #docsearch-input{
                min-width: var(--sf-f7);
            }
            .sf-menu-container{
                place-items: center;
                display: flex;
                gap: var(--sf-d7);
            }

            .sf-menu{
                display: inline-flex;
                gap: var(--sf-c2);
            }

            .sf-menu .sf-menu-item{
                display: inline-flex;
                font-size: var(--sf-text--size-1);
                line-height: var(--sf-text--height-1);
                //padding-left: var(--sf-b2);
                //padding-right: var(--sf-b2);

            }
            .sf-menu .sf-menu-item a{
                color: var(--sf-on-surface);
                font-weight: var(--sf-text--font-weight-5);
            }

            .sf-button.sf-button--nav-switch{
                --sf-button--text-size: var(--sf-text--size-3);
                max-width: var(--sf-d0);
                justify-content: center;
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

        .sf-breadcrumb{
            padding-bottom: var(--sf-space-3);
        }

        .sf-input-close {
        --sf-close--diameter: var(--sf-text--height-1);
            /* position: absolute; */
            display: flex;
            justify-content: center;
            align-items: anchor-center;
            width: var(--sf-close--diameter);
            height: var(--sf-close--diameter);
            font-size: var(--sf-text--height-1);
            color: var(--sf-outline);
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .sf-input-search-container{
            width: var(--sf-f7) !important;
        }
        /*.sf-input-close:before, .sf-input-close:after {
            position: absolute;
            left: 50%;
            content: " ";
            height: 100%;
            width: 2px;
            top: 0;
            background-color: var(--sf-outline);
        }
        .sf-input-close:before {
            transform: rotate(45deg);
        }
        .sf-input-close:after {
            transform: rotate(-45deg);
        }*/

        </style>
</head>

<body class="theme-light flex flex-col justify-between min-h-screen leading-normal max-container-6">
<header class="w-full flex p-top-1 p-bottom-1 md:p-top-1 md:p-bottom-1 container p-left-0 p-right-0" role="banner">
    <!--<div class="flex items-center">--> <!--sf-container sf-container-header mx-auto px-4 lg:px-8-->
        <div class="sf-menu-container">
            <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                <svg width="120" height="32" viewBox="0 0 120 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32.301 0H0V31.9997H32.301V0Z" fill="#E81123"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.68896 16.0001L13.3394 7.39781L16.1506 10.1934L10.3108 16.0008L16.1495 21.8071L13.339 24.602L4.68896 16.0001Z" fill="#F7F7F7"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M27.6123 16.0001L18.9619 7.39781L16.1507 10.1934L21.9905 16.0008L16.1517 21.8071L18.9623 24.602L27.6123 16.0001Z" fill="#F7F7F7"></path>
                    <path d="M61.6324 2.37348C61.6324 3.6842 60.5598 4.74678 59.2367 4.74678C57.9137 4.74678 56.8412 3.6842 56.8412 2.37348C56.8412 1.06275 57.9137 0.000167847 59.2367 0.000167847C60.5598 0.000167847 61.6324 1.06275 61.6324 2.37348Z" fill="var(--sf-on-surface)"></path>
                    <path d="M87.7682 24.248V14.8872C87.7682 12.863 86.109 11.2194 84.0658 11.2194C82.0223 11.2194 80.3635 12.863 80.3635 14.8872V24.248H76.4434V14.8872C76.4408 12.8648 74.7828 11.2237 72.7411 11.2237C70.6975 11.2237 69.0388 12.867 69.0388 14.8915V24.248H65.1168L65.1187 7.68679H69.0388V8.63468C70.0478 7.77019 71.3977 7.34014 72.7411 7.34014C74.9861 7.34014 77.006 8.30386 78.4013 9.83602C79.7969 8.30133 81.8183 7.33581 84.0658 7.33581C88.2729 7.33581 91.6882 10.7195 91.6882 14.8872V24.248H87.7682Z" fill="var(--sf-on-surface)"></path>
                    <path d="M61.1968 7.68096H57.2767V24.2479H61.1968V7.68096Z" fill="var(--sf-on-surface)"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M107.804 22.8413C106.777 23.8269 104.782 24.5961 103.013 24.5961C98.2048 24.5961 94.3018 20.729 94.3018 15.9659C94.3018 11.2028 98.2048 7.33581 103.013 7.33581C104.782 7.33581 106.516 8.11865 107.804 9.10427V7.67993H111.725V24.2469H107.804V22.8413ZM107.978 15.9659C107.978 13.3463 105.832 11.2194 103.187 11.2194C100.543 11.2194 98.3961 13.3463 98.3961 15.9659C98.3961 18.5855 100.543 20.7125 103.187 20.7125C105.832 20.7125 107.978 18.5855 107.978 15.9659Z" fill="var(--sf-on-surface)"></path>
                    <path d="M119.565 7.68096H115.645V24.2479H119.565V7.68096Z" fill="var(--sf-on-surface)"></path>
                    <path d="M117.605 4.74678C118.927 4.74678 120 3.6842 120 2.37348C120 1.06275 118.927 0.000167847 117.605 0.000167847C116.281 0.000167847 115.209 1.06275 115.209 2.37348C115.209 3.6842 116.281 4.74678 117.605 4.74678Z" fill="var(--sf-on-surface)"></path>
                    <path d="M40.2896 21.5583C42.0732 23.6698 45.0681 24.5961 47.3421 24.5961C49.3268 24.5961 50.8977 24.1243 52.0557 23.1807C53.2131 22.2372 53.7921 20.9599 53.7921 19.3489C53.7921 17.6459 53.0397 16.2137 51.5061 15.3619C50.7166 14.9231 48.7033 14.3206 47.5522 13.976C47.4089 13.9331 47.279 13.8943 47.1664 13.8602C47.1462 13.8541 47.1246 13.8476 47.1017 13.8407C46.4614 13.6483 44.8356 13.1597 44.8804 12.1342C44.9189 11.2622 45.8372 10.7879 47.3258 10.7879C48.4186 10.7879 50.108 11.1216 51.2241 12.5829L53.705 10.1665C52.6418 8.79143 50.8404 7.33581 47.4321 7.33581C46.1326 7.33581 44.4989 7.58324 43.5516 8.21613C42.0761 9.20211 41.1592 10.4369 41.1592 12.4448C41.1592 13.7796 41.9922 15.328 43.4452 16.1558C44.3788 16.6876 46.5748 17.4478 48.051 17.8645C49.1613 18.1781 50.1163 18.6438 50.0709 19.6597C50.0259 20.6701 48.7297 21.286 47.4129 21.2303C44.9388 21.1253 43.361 19.8092 42.7705 19.1418L40.2896 21.5583Z" fill="var(--sf-on-surface)"></path>
                </svg>

                <!--<h1 class="text-lg md:text-2xl text-blue-900 font-semibold hover:text-blue-600 my-0 pr-4">{{ $page->siteName }}</h1>-->
            </a>
                <div class = "sf-menu">
                    <div class = "sf-menu-item">
                        <a href="#"> Концепция </a>
                    </div>
                    <div class = "sf-menu-item">
                        <a href="#"> Ядро </a>
                    </div>
                    <div class = "sf-menu-item">
                        <a href="#"> Утилиты </a>
                    </div>
                    <div class = "sf-menu-item">
                        <a href="#"> Компоненты </a>
                    </div>
                    <div class = "sf-menu-item">
                        <a href="#"> Смарт-компоненты </a>
                    </div>
                </div>
        </div>

        <div class="flex flex-1 justify-end items-center text-right md:pl-10 gap-x-1">
            @include('_core._nav.search-input')

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
        
        
    <!--</div>-->

    @yield('nav-toggle')
</header>
<div class="w-full flex flex-auto justify-center container p-left-0 p-right-0">
     <!--sf-container sf-container-main-->
        <aside class = "sf-nav-menu sf-nav-menu--right">
            <nav id="js-nav-menu" class="nav-menu hidden lg:block">
                @include('_core._nav.menu', ['items' => $page->navigation])
            </nav>
        </aside>
        <main role="main" class="w-full break-words ">
            @yield('body')
        </main>
        <aside class = "sf-nav-menu side-menu">
            <nav id="js-nav-menu" class="nav-menu hidden lg:block">
                @include('_core._nav.side-menu', ['items' => $page->navigation])
            </nav>
        </aside>
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
