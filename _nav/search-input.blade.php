<div class="search--wrap flex justify-end items-center ml-auto mr-4  text-right md:pl-10">
    <button
            title="Start searching"
            type="button"
            class="flex md:hidden bg-gray-100 hover:bg-blue-100 justify-center items-center border border-gray-500 rounded-full focus:outline-none h-10 px-3"
            onclick="searchInput.toggle()"
    >
        <img src="{{mix('/img/magnifying-glass.svg', 'assets/build')}}" alt="search icon" class="h-4 w-4 max-w-none">
    </button>

    <div id="js-search-input" class="docsearch-input__wrapper hidden md:block">
        <div id="search_results" class="docsearch-input__holder hidden"></div>
        <label for="search" class="hidden">Search</label>

        <input
                id="docsearch-input"
                class="docsearch-input relative block h-10 transition-fast  outline-none text-gray-700 border  ml-auto px-4 pb-0"
                name="docsearch"
                type="text"
                placeholder="Search"
        >

        <button
                class="md:hidden absolute pin-t pin-r h-full font-light text-3xl text-blue-500 hover:text-blue-600 focus:outline-none -mt-px pr-7"
                onclick="searchInput.toggle()"
        >&times;
        </button>
    </div>
</div>


