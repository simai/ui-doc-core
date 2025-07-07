<header class="w-full flex" role="banner">
    <div class="header--wrap flex items-cross-center container gap-1">
        @include('_core._layouts.logo')

        @include('_core._nav.top-menu')

        <div class="flex flex-1 justify-end items-center text-right md:pl-10 gap-x-1">

            @include('_core._nav.search-input')

            <div class="header--right flex gap-1 md:gap-1 relative">
                <button class="sf-button sf-button-search sf-button--1 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
                    <i class="sf-icon">search</i>
                </button>

                @include('_core._components.language')
                @include('_core._components.settings')
                @include('_core._components.more')
{{--                <button class="sf-button sf-theme-button  sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch"--}}
{{--                        id="theme-toggle">--}}
{{--                    <i class="sf-icon icon-dark">dark_mode</i>--}}
{{--                    <i class="sf-icon icon-light">light_mode</i>--}}
{{--                </button>--}}
                <button onclick="toggleMobileMenu(this)" class="sf-button sf-button-nav sf-button--on-surface-transparent sf-button--borderless sf-button--nav-switch">
                    <i class="sf-icon">menu</i>
                </button>
            </div>
        </div>
    </div>
</header>
