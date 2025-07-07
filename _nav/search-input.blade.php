<div id="search_doc" class="search--wrap flex content-main-end ml-auto  text-right ">
    <script>
        window.sfSearchNotFound = '{{$page->translate('notFound')}}'
    </script>
    <div class="sf-input-container sf-input-container--1 sf-input-search-container grow-none">
        <div class="sf-input sf-input--1" id="input_search">
            <div class="flex">
                <i class="sf-icon">search</i>
            </div>
            <label class="sf-input-inner-label">
                <input name="search" type="text" class="sf-input-main" placeholder="{{$page->translate('search')}}">
            </label>
            <div class="sf-input-body--right flex flex-center items-cross-center absolute right-0 hidden">
                <button class="sf-input-close">
                    <i class="sf-icon">close</i>
                </button>
            </div>
        </div>
        <div id="search_results" class="docsearch-input__holder hidden">
            <div class="docsearch-input__main flex flex-col"></div>
        </div>
    </div>
</div>



