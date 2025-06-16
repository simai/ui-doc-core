export class SearchClass {
    constructor({fuse}) {
        this.value = '';
        this.fuse = fuse;
        this.init();
    }

    init() {
        this.wrap = document.getElementById('search_doc');
        this.searchButton = document.querySelector('.sf-button-search');
        this.inputContainer = this.wrap.querySelector('.sf-input-search-container');
        this.menu = document.querySelector(".sf-menu-container");
        this.input = document.getElementById('input_search').querySelector('input');
        this.resultsWrap = document.getElementById('search_results');
        this.resultsContainer = this.resultsWrap.querySelector('.docsearch-input__main');
        this.logo = document.querySelector('a.logo');
        this.headerRight = document.querySelector('.header--right');
        this.close = this.wrap.querySelector('.sf-input-close');
        [this.searchButton, this.input].forEach((item) => {
            item.addEventListener('click', () => {
                this.openSearch();
            });
        });
        this.close.addEventListener('click', () => {
            this.value = '';
            this.input.value = '';
            this.clearResults();
            this.closeState(this.value !== '');

        });
        this.initDoc();

    }

    clearResults() {
        this.resultsContainer.innerHTML = '';
        this.resultsWrap.classList.add('hidden');
    }

    initDoc() {
        if (this.input) {
            ['input', 'focus'].forEach(event => {
                this.input.addEventListener(event, (e) => {
                    const value = e.target.value.trim();
                    if (this.value === value) {
                        return;
                    }
                    if (value.length > 2) {
                        const results = this.fuse.search(e.target.value.trim());
                        if (event === 'focus') {
                            setTimeout(() => {
                                this.renderResults(results);
                            }, 400);
                        } else {
                            this.value = e.target.value;
                            this.renderResults(results);
                            this.closeState(this.value !== '');
                        }
                    }
                    if (!value.length) {
                        this.resultsWrap.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('click', (event) => {
                if (event.target !== this.wrap && event.target !== this.searchButton && !this.searchButton.contains(event.target) && !this.wrap.contains(event.target)) {
                    this.closeSearch();
                }
            });
        }
    }

    highlightMatch(text, indices) {
        if (!indices || !indices.length) return text;

        let result = '';
        let lastIndex = 0;

        for (const [start, end] of indices) {
            result += text.slice(lastIndex, start);
            result += '<mark>' + text.slice(start, end + 1) + '</mark>';
            lastIndex = end + 1;
        }

        result += text.slice(lastIndex);
        return result;
    }

    getExcerptWithHighlightSmart(text, indices, context = 60) {
        if (!indices?.length) return text;
        indices.sort((a, b) => (a[1] - a[0]) - (b[1] - b[0]));
        const [start, end] = indices[indices.length - 1];

        const firstNewlineIndex = text.indexOf('\n');
        const isInFirstLine = firstNewlineIndex === -1 || start < firstNewlineIndex;


        if (isInFirstLine && (firstNewlineIndex === -1 || firstNewlineIndex <= context * 2)) {
            const lineEnd = firstNewlineIndex === -1 ? text.length : firstNewlineIndex;
            const before = text.slice(0, start);
            const match = text.slice(start, end + 1);
            const after = text.slice(end + 1, lineEnd);
            return `${before}<mark>${match}</mark>${after}`;
        }

        const excerptStart = Math.max(start - context, 0);
        const excerptEnd = Math.min(end + context + 1, text.length);
        const before = text.slice(excerptStart, start);
        const match = text.slice(start, end + 1);
        const after = text.slice(end + 1, excerptEnd);

        return `${excerptStart > 0 ? '…' : ''}${before}<mark>${match}</mark>${after}${excerptEnd < text.length ? '…' : ''}`;
    }

    renderResults(results, containerId = 'search_results') {
        this.resultsContainer.innerHTML = '';

        if (!results.length) {
            const text = window.sfSearchNotFound !== 'undefined' ? window.sfSearchNotFound : 'Ничего не найдено';
            this.resultsContainer.innerHTML = `<p class="search--result-content text-left">${text}</p>`;
            this.resultsWrap.classList.remove('hidden');
            return;
        }
        results.forEach(result => {
            const {item, matches} = result;

            let highlightedTitle = item.title;
            let highlightedContent = item.content?.slice(0, 200) || '';
            matches.forEach(match => {
                if (match.key === 'title') {
                    highlightedTitle = this.highlightMatch(match.value, match.indices);
                } else if (match.key === 'content') {
                    highlightedContent = this.getExcerptWithHighlightSmart(item.content, match.indices);
                }
            });

            const block = document.createElement('div');
            block.className = 'search--result text-left';
            block.innerHTML = `
      <a class="flex flex-col" href="${item.url}">
        <div class="search--result-title">${highlightedTitle}</div>
              <p class="search--result-content">${highlightedContent}</p>
      </a>
    `;

            this.resultsContainer.appendChild(block);
            this.resultsWrap.classList.remove('hidden');
        });
    }

    closeState(state) {
        if (!state) {
            this.close.parentNode.classList.add('hidden');
        } else {
            this.close.parentNode.classList.remove('hidden');
        }
    }

    searchGetter() {
        return this.input;
    }

    menuGetter() {
        return this.menu;
    }

    closeSearch() {
        this.resultsWrap.classList.add('hidden');
        this.inputContainer.classList.add('grow-none');
        this.inputContainer.classList.remove('grow');
        this.wrap.classList.remove('open');
        if (window.innerWidth < 520) {
            this.wrap.classList.remove('visible');
        }
        if (window.innerWidth < 768) {
            this.logo.classList.remove('hidden');
            this.headerRight.classList.remove('hidden');
        } else {
            this.menu.classList.remove('hidden');
        }

    }

    openSearch() {
        this.inputContainer.classList.remove('grow-none');
        this.inputContainer.classList.add('grow');
        this.wrap.classList.add('open');
        if (window.innerWidth < 520) {
            this.wrap.classList.add('visible');
        }
        if (window.innerWidth < 768) {
            this.logo.classList.add('hidden');
            this.headerRight.classList.add('hidden');
        } else {
            this.menu.classList.add('hidden');
        }
    }

    searchDocumentClick() {
        if (!this.input.contains(event.target)) {
        }
    }
}
