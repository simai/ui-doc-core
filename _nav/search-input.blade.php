<div class="search--wrap flex justify-end items-center ml-auto  text-right ">

    <div class="sf-input-container sf-input-container--1 sf-input-search-container">
        <div class="sf-input sf-input--1 radius-0" id="input_search">
            <div class="flex">
                <i class="sf-icon">search</i>
            </div>
            <label class="sf-input-inner-label">
                <input type="email" required="" class="sf-input-main" placeholder="Search" >
            </label>
            <div class="sf-input-body--right flex flex-center items-cross-center">
                <button class="sf-input-close" style = "display: none;">✕</button>
            </div>
        </div>
        <div id="search_results" class="docsearch-input__holder hidden"></div>
    </div>
</div>

<script>
    class SearchClass{
        constructor(){
            this.search = document.querySelector("#input_search");
            this.menu = document.querySelector(".sf-menu");
        }
        searchGetter(){
            return this.search;
        }
        menuGetter(){
            return this.menu;
        }
        toggleSearch(){
            console.log("You clicked search!");
            //const menu = document.querySelector(".sf-menu");
            this.menu.style.display = "none";
            this.search.parentElement.classList.add('flex-grow');
        }
        searchDocumentClick(){
            if (!this.search.contains(event.target)) {
                console.log(event.target);
                /// The click was OUTSIDE the specifiedElement, do something
                console.log("You clicked outside of search!");
            }
        }
    }
    const searchObject = new SearchClass();

    searchObject.searchGetter().addEventListener("click", function(){
        searchObject.toggleSearch();
    });

    searchObject.searchGetter().querySelector('input').addEventListener('input', function() {
        if (this.value.trim() !== '') {
            searchObject.searchGetter().querySelector('.sf-input-close').style.display = 'flex';
        } else {
            searchObject.searchGetter().querySelector('.sf-input-close').style.display = 'none';
        }
    });

    searchObject.searchGetter().querySelector('.sf-input-close').addEventListener('click', function() {
        searchObject.searchGetter().querySelector('input').value = '';
        searchObject.searchGetter().querySelector('input').dispatchEvent(new Event("input", { bubbles: true }));
    });

    document.addEventListener('click', event => {
            const isClickInside = searchObject.searchGetter().contains(event.target);

            if (!isClickInside) {
                searchObject.menuGetter().style.display = "inline-flex";
                searchObject.search.parentElement.classList.remove('flex-grow');
            }
        });


</script>

