<header class="w-full flex" role="banner">
    <div class="header--wrap flex items-cross-center container gap-1">
        @include('_core._layouts.logo')

        @if($page->category)
            @include('_core._nav.top-menu')
        @endif

        <div class="flex flex-1 justify-end items-center text-right md:pl-10 gap-x-1">

            @include('_core._nav.search-input')

            <div class="header--right flex gap-1 md:gap-1 relative">
                <button class="sf-icon-button sf-button-search sf-icon-button--size-1 sf-icon-button--link sf-icon-button--on-surface side-menu-instrument">
                    <i class="sf-icon">search</i>
                </button>
                <button id="read_mode" onclick="applyReadMode(this)"
                        class="sf-icon-button sf-button-readMode sf-icon-button--size-1 sf-icon-button--link sf-icon-button--on-surface side-menu-instrument">
                    <i class="sf-icon">fullscreen</i>
                </button>
                @include('_core._components.language')
                @include('_core._components.settings')
                @include('_core._components.more')
                <button onclick="toggleMobileMenu(this)"
                        class="sf-icon-button sf-button-nav sf-icon-button--size-1 sf-icon-button--on-surface sf-icon-button--link sf-button--nav-switch">
                    <i class="sf-icon">menu</i>
                </button>
            </div>
        </div>
    </div>
</header>
