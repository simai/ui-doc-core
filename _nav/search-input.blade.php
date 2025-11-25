<div id="search_doc" class="search--wrap flex content-main-end ml-auto  text-right ">
    <script>
        window.sfSearchNotFound = '{{$page->translate('notFound')}}'
    </script>
    <div class="sf-input-container sf-input-container--1 sf-input-search-container grow-none">
        <div class="sf-input sf-input--size-1 sf-input--decoration-bordered s">
            <div class="sf-input-field">
                <i class="sf-icon">search</i>
                <input name="search" type="text" class="sf-input-main" placeholder="{{$page->translate('search')}}">
            </div>
        </div>

        <div id="search_results" class="docsearch-input__holder hidden">
            <div class="docsearch-input__main flex flex-col"></div>
        </div>
    </div>
</div>



