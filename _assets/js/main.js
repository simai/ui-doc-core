// import hljs from 'highlight.js/lib/core';

import Fuse from 'fuse.js'
import {SearchClass} from "./helpers/Search.js";

const locale = getCookie('locale') ?? 'ru';

// hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash'));
// hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));
// hljs.registerLanguage('html', require('highlight.js/lib/languages/xml'));
// hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
// hljs.registerLanguage('json', require('highlight.js/lib/languages/json'));
// hljs.registerLanguage('markdown', require('highlight.js/lib/languages/markdown'));
// hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
// hljs.registerLanguage('scss', require('highlight.js/lib/languages/scss'));
// hljs.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));


window.toggleNav = (btn) => {
    btn.closest('.sf-nav-menu-element').classList.toggle('active');
}

let fuse = null;


async function initSearch() {
    fuse = await initFuse();
    const Search = new SearchClass({
        fuse: fuse
    });
    // const {input, menu} = Search;
    //
    //
    //
    // input.querySelector('.sf-input-close').addEventListener('click', function() {
    //     input.querySelector('input').value = '';
    //     input.querySelector('input').dispatchEvent(new Event("input", { bubbles: true }));
    // });
}

async function initFuse() {
    return await fetch(`/search-index_${locale}.json`)
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
            return new Fuse(data, options);
        });
}


function initClicks(navLinks, header) {
    navLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const headerHeight = header ? header.offsetHeight : 0;
                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - headerHeight;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    })
}

function initNavLinks() {
    const headers = document.querySelectorAll("h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]");
    const navLinks = document.querySelectorAll(".sf-side-menu-list-item a");
    const header = document.querySelector("header");
    const visibleIds = new Set();
    const headerHeight = header ? header.offsetHeight : 0;
    initClicks(navLinks, header)
    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                const id = entry.target.getAttribute("id");
                const link = document.querySelector(`.sf-side-menu-list-item a[href="#${id}"]`);

                if (!link) return;

                if (entry.isIntersecting) {
                    visibleIds.add(id);
                } else {
                    visibleIds.delete(id);
                }
            });
            let hasActive = false;
            navLinks.forEach((link, index) => {
                const href = link.getAttribute("href") || "";
                const id = href.startsWith("#") ? href.slice(1) : null;

                if (id && visibleIds.has(id)) {
                    hasActive = true;
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
            if(!hasActive) {
                const last = navLinks[navLinks.length - 1];
                last.classList.add("active");
            }
        },
        {
            rootMargin: `-${headerHeight}px 0px 0px 0px`,
            threshold: 0
        }
    );

    headers.forEach(header => observer.observe(header));
}


function init() {
    initNavLinks();
    initResize();
    initSearch();
}

if (typeof Turbo !== 'undefined') {
    document.addEventListener('turbo:load', init);
} else {
    document.addEventListener("DOMContentLoaded", init);
}

function getInitialState() {
    const savedState = localStorage.getItem('containerExpanded');
    return savedState ? savedState === 'true' : false;
}

// Применяем сохраненное состояние
function applyState(isExpanded, resizeButton, contentContainer) {
    let number = 0;
    if (isExpanded) {
        contentContainer.classList.add('container-expanded');
        contentContainer.classList.remove('container-default');

        const containerClasses = [...contentContainer.classList].filter(className =>
            className.startsWith('max-container')
        );
        // Получить полное название класса
        if (containerClasses.length > 0) {
            const fullClassName = containerClasses[0];
            // Можно извлечь число из класса
            const match = fullClassName.match(/max-container-(\d+)/);
            if (match) {
                number = Number(match[1]) + 2;
                contentContainer.classList.remove(containerClasses[0]);
                contentContainer.classList.add('max-container-' + number);
            }
        }
        [...resizeButton.querySelectorAll('.sf-size-switcher--expanded')].forEach(element => {
            element.style.display = "flex";
        });
        resizeButton.querySelector('.sf-size-switcher--default').style.display = "none";
    } else {
        const containerClasses = [...contentContainer.classList].filter(className =>
            className.startsWith('max-container')
        );
        if (contentContainer.classList.contains('container-expanded')) {
            // Получить полное название класса
            if (containerClasses.length > 0) {
                const fullClassName = containerClasses[0];
                // Можно извлечь число из класса
                const match = fullClassName.match(/max-container-(\d+)/);
                if (match) {
                    number = Number(match[1]) - 2;
                    contentContainer.classList.remove(containerClasses[0]);
                    contentContainer.classList.add('max-container-' + number);
                }
            }
        }
        [...resizeButton.querySelectorAll('.sf-size-switcher--expanded')].forEach(element => {
            element.style.display = "none";
        });
        resizeButton.querySelector('.sf-size-switcher--default').style.display = "flex";
        contentContainer.classList.remove('container-expanded');
        contentContainer.classList.add('container-default');
    }
}


function initResize() {
    const resizeButton = document.querySelector('.sf-size-switcher');
    const contentContainer = document.querySelector('body');


    let isExpanded = getInitialState();
    applyState(isExpanded, resizeButton, contentContainer);

}

window.toggleResize = function (button) {
    const isExpanded = !getInitialState(),
        contentContainer = document.querySelector('body');
    localStorage.setItem('containerExpanded', isExpanded.toString());

    applyState(isExpanded, button, contentContainer);
}


window.readMode = function () {
    const header = document.querySelector('header');
    const navMenu = document.querySelector('.sf-nav-menu--right');
    const sideMenu = document.querySelector('.side-menu');
    const mainContainer = document.querySelector('.container--main');
    const sideMenuNavigation = document.querySelector('.side-menu-navigation');
    [header, navMenu, sideMenuNavigation].forEach(item => {
        if (item) {
            item.classList.toggle('hidden');
        }
    });

    [mainContainer, sideMenu].forEach(item => {
        if (item) {
            item.classList.toggle('read');
        }
    })

}

window.langOpen = function (item) {

    const language_switch_panel = item.parentElement.querySelector('.sf-language-switch--language-panel');
    if (language_switch_panel.classList.contains("sf-language-switch--language-panel-show"))
        language_switch_panel.classList.remove("sf-language-switch--language-panel-show");
    else
        language_switch_panel.classList.add("sf-language-switch--language-panel-show");

}

window.langSwitch = function (button) {
    const newLocale = button.dataset.locale;
    if (newLocale !== locale) {
        document.cookie = `locale=${newLocale}; path=/; max-age=31536000`; // 1 year
        const currentPath = window.location.pathname.split('/');
        const currentLocale = locale;
        window.location.href = currentPath.map((segment, index) =>
            segment === currentLocale ? newLocale : segment
        ).join('/');
    }
}

window.addEventListener('click', function (e) {
    const container = document.querySelector('.sf-language-switch--container');
    const menu = container.querySelector('.sf-language-switch--language-panel-show');
    if (!menu) {
        return false;
    }
    if (e.target !== container && !container.contains(e.target)) {
        document.querySelector('.sf-language-switch--language-panel').classList.remove("sf-language-switch--language-panel-show");
    }
});


// document.querySelectorAll('pre code').forEach((block) => {
//     hljs.highlightBlock(block);
// });
