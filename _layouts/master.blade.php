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


            /*dropdown delete later*/
            .sf-dropdown {
            position: relative;
            display: inline-block;
            //width: 200px; /* Ширина dropdown */
            --sf-dropdown--font-size: var(--sf-text--size-1);
            --sf-dropdown--color: var(--sf-on-surface);

            font-size: var(--sf-dropdown--font-size);
            
            &--1\/3{
                --sf-dropdown--font-size: var(--sf-text--size-1\/3);
            }
            &--1\/2{
                --sf-dropdown--font-size: var(--sf-text--size-1\/2);
            }
            &--2{
                --sf-dropdown--font-size: var(--sf-text--size-2);
            }
            &--3{
                --sf-dropdown--font-size: var(--sf-text--size-3);
            }
        }
        
        /* Стили для input */
        /*.sf-dropdown-input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }*/
        
        /* Стили для выпадающего меню */
        .sf-dropdown-menu {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            top: 80%; /* Размещаем меню вплотную под .sf-input */
            left: 0;
            width: 100%;
            max-height: 150px; /* Максимальная высота меню */
            overflow-y: auto; /* Добавляем прокрутку, если элементов много */
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            margin: 0;
            padding: 0;
            list-style: none;
            border: 1px solid #ccc;
            border-top: none;

            .sf-icon{
                display: none;
                color: var(--sf-primary);
            }

            input[type="checkbox"]{
                display:none;
            }

            input[type="checkbox"]:checked + .sf-icon{
                display: flex;
            }
            .sf-dropdown-menu-item--active{
                background-color: var(--sf-primary-transparent-active);
            }
            
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
        </style>

        <script>
                document.addEventListener('DOMContentLoaded', function() {

        // Проверяем сохранённую тему или системные настройки
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // Определяем тему
        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        console.log(document.body);
        // Применяем класс сразу
             document.body.classList.add('theme-' + theme);
        });
    </script>
</head>

<body class="theme-light flex flex-col justify-between min-h-screen leading-normal">
<header class="w-full flex p-top-1 p-bottom-1 md:p-top-1 md:p-bottom-1 " role="banner">
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
            <button class = "sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch" id = "lang_switch">
                <i class = "sf-icon">Language</i>
            </button>
            <div class="sf-dropdown">
                <div class="sf-input-container">
                        <span class="sf-input--top-label">Label</span>
                        <div class="flex items-cross-center sf-input sf-input--1/3 sf-input-email" sf-code="sf-input-body" id="input_email">
                        <div class="flex">
                            <i class="sf-icon">mail</i>
                        </div>
                        <label class="sf-input-inner-label">
                            <input type="email" required="" class="sf-input-main sf-dropdown-input" placeholder="">
                            <span>Введите почту</span>
                        </label>
                        <div class="sf-input-body--right flex flex-center items-cross-center">
                            
                            <i class="sf-icon">keyboard_arrow_down</i>
                        </div>
                        </div>
            
                        <ul class="sf-dropdown-menu show">
                        <label><li class="sf-dropdown-menu-item"><span>Option 1</span> <input type="checkbox" name="test" value="value1"> <i class="sf-icon sf-icon-light">check</i></li></label>
                        <label><li class="sf-dropdown-menu-item"><span>Option 2</span> <input type="checkbox" name="test" value="value2"> <i class="sf-icon sf-icon-light">check</i></li></label>
                        <label><li class="sf-dropdown-menu-item"><span>Option 3</span> <input type="checkbox" name="test" value="value3"> <i class="sf-icon sf-icon-light">check</i></li></label>
                        <label><li class="sf-dropdown-menu-item"><span>Option 4</span> <input type="checkbox" name="test" value="value4"> <i class="sf-icon sf-icon-light">check</i></li></label>
                        </ul>
                        <span class="sf-input--bottom-label">This is a hint text to help user.</span>
                </div>
            </div>
            <button class = "sf-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch" id = "theme-toggle">
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
    document.getElementById('lang_switch').addEventListener('click', function(){
        console.log('{{ $locale }}');
        const loc_switch = '{{ $locale }}';
        console.log(document.getElementById('locale').value);
        if(loc_switch == 'ru')
            document.getElementById('locale').value='en';
        else 
            document.getElementById('locale').value='ru';
        document.getElementById('locale').dispatchEvent(new Event("change", { bubbles: true }));

        //this.get('')
        //const currentPath = window.location.pathname.split('/');
        
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

    


    document.getElementById('theme-toggle').addEventListener('click', () => {
        const html = document.body;
        const isDark = html.classList.contains('theme-dark');
        
        // Переключаем тему
        html.classList.remove(isDark ? 'theme-dark' : 'theme-light');
        html.classList.add(isDark ? 'theme-light' : 'theme-dark');
        
        // Сохраняем в localStorage
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });

</script>
@stack('scripts')

</body>
</html>
