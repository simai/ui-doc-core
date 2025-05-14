import hljs from 'highlight.js/lib/core';

import Fuse from 'fuse.js'


hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash'));
hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));
hljs.registerLanguage('html', require('highlight.js/lib/languages/xml'));
hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
hljs.registerLanguage('json', require('highlight.js/lib/languages/json'));
hljs.registerLanguage('markdown', require('highlight.js/lib/languages/markdown'));
hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
hljs.registerLanguage('scss', require('highlight.js/lib/languages/scss'));
hljs.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));


window.toggleNav = (btn) => {
    btn.parentNode.classList.toggle('active');
}


let fuse = null;
const locale = getCookie('locale') ?? 'ru';

fetch(`/search-index_${locale}.json`)
    .then(response => response.json())
    .then(data => {

        const options = {
            keys: [
                "title",
                "content",
                "headings.text"
            ],
            threshold: 0.0,
            ignoreLocation: true,
            distance: 0,
            minMatchCharLength: 3,
            includeScore: true,
            findAllMatches: true,
            includeMatches: true
        };
        fuse = new Fuse(data, options);
    });

function highlightMatch(text, indices) {
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

function getExcerptWithHighlightSmart(text, indices, context = 60) {
    if (!indices?.length) return text;
    indices.sort((a,b) => (a[1] - a[0]) - (b[1] - b[0]))
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


function renderResults(results, containerId = 'search_results') {
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (!results.length) {
        container.innerHTML = '<p>Ничего не найдено</p>';
        return;
    }
    results.forEach(result => {
        const { item, matches } = result;

        let highlightedTitle = item.title;
        let highlightedContent = item.content?.slice(0, 200) || '';
        matches.forEach(match => {
            if (match.key === 'title') {
                highlightedTitle = highlightMatch(match.value, match.indices);
            } else if (match.key === 'content') {
                highlightedContent = getExcerptWithHighlightSmart(item.content, match.indices);
            }
        });

        const block = document.createElement('div');
        block.className = 'search--result';
        block.innerHTML = `
      <a href="${item.url}">
        <div class="search--result-title">${highlightedTitle}</div>
      </a>
      <p class="search--result-content">${highlightedContent}</p>
    `;

        container.appendChild(block);
        container.classList.remove('hidden');
    });
}
const docInput = document.getElementById('docsearch-input');


if(docInput) {
    ['input','focus'].forEach(event => {
        docInput.addEventListener(event, function (e) {
            const value = e.target.value;
            if (value.length > 2) {
                const results = fuse.search(e.target.value.trim());
                renderResults(results)
            }
        });
    })

    document.addEventListener('click', (event) => {
        const container  = document.getElementById('search_results');
        const wrap = document.getElementById('js-search-input');
        if(container && wrap) {
            if (event.target !== wrap && !wrap.contains(event.target)) {
                container.classList.add('hidden');
            }
        }
    })
}

document.querySelectorAll('pre code').forEach((block) => {
    hljs.highlightBlock(block);
});
