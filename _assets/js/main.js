import Fuse from 'fuse.js';
import {SearchClass} from "./helpers/Search.js";
import {SizeObserver} from "./helpers/ResizeObserver";
import setReadModePosition from "./helpers/functions";

const locale = getCookie('locale') ?? 'ru';


window.toggleNav = (btn) => {
    btn.closest('.sf-nav-menu-element').classList.toggle('active');
};

let fuse = null;


async function initSearch() {
    if (!fuse) {
        fuse = await initFuse();
    }
    const Search = new SearchClass({
        fuse: fuse
    });
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

window.copyAnchor = function(link) {
    navigator.clipboard.writeText(link.href);
};


function initClicks(navLinks, header) {
    navLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (window.innerWidth < 768) {
                window.navOpen();
            }
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const headerHeight = header ? header.offsetHeight : 0;
                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - headerHeight;

                window.scrollTo({
                    top: offsetPosition - 20,
                    behavior: 'smooth'
                });
            }
        });
    });
}


window.toggleMobileMenu = function (button) {
    const mainMenu = document.getElementById('main_menu');
    const icon = button.querySelector('.sf-icon');
    const navMenu = document.getElementById('side_menu');
    if (mainMenu) {
        mainMenu.classList.toggle('active');
        if (mainMenu.classList.contains('active')) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
        icon.textContent = mainMenu.classList.contains('active') ? 'close' : 'menu';
    }
    if (navMenu) {
        navMenu.classList.remove('active');
    }

};


window.toggleSettings = function (button) {
    const wrap = button.parentNode;
    wrap.classList.toggle('active');
};


window.addEventListener('Switch:render', (event) => {
    const {detail} = event;
    if (detail) {
        const input = detail.querySelector('input');
        switch (detail.id) {
            case 'theme_switch':
                input.checked = SF.Loader.theme === 'dark';
                input.addEventListener('change', event => {
                    console.log('change');
                    SF.Loader.changeTheme();
                });
                break;
            case 'widescreen_switch':
                input.checked = getInitialState('expanded');
                input.addEventListener('change', () => {
                    toggleResize();
                });
                break;
        }
    }
});

window.setIssue = function () {
    const selection = window.getSelection();
    const text = selection ? selection.toString() : '';
    if (text.length > 0) {
        const issueUrl = 'https://github.com/simai/ui-doc-template/issues/new?'
            + 'title=' + encodeURIComponent('Issue: ' + text.slice(0, 60))
            + '&body=' + encodeURIComponent('**Выделено:**\n\n' + text);
        window.open(issueUrl, '_blank');
    }
};


function initNavLinks() {
    console.warn('Доработать якоря, если у них уже есть id ');
    console.warn('Проработать систему размещения кнопки выхода из полного режима при ресайзе + убирать этот режим, если значение меньше 768.');
    console.warn('Проработать поведение состояния this.setReadInside в зависимости от места на экране');
    const headers = document.querySelectorAll("h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]");
    const navLinks = document.querySelectorAll(".sf-side-menu-list-item a");
    const header = document.querySelector("header");
    const visibleIds = new Set();
    const headerHeight = header ? header.offsetHeight : 0;
    initClicks(navLinks, header);
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
            navLinks.forEach((link) => {
                navClick(link, headerHeight);
                const href = link.getAttribute("href") || "";
                const id = href.startsWith("#") ? href.slice(1) : null;

                if (id && visibleIds.has(id)) {
                    hasActive = true;
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
            if (!hasActive) {
                const last = navLinks[navLinks.length - 1];
                last.classList.add("active");
            }
        },
        {
            rootMargin: `-${headerHeight}px 0px 0px 0px`,
            threshold: 0
        }
    );

    headers.forEach(h => {
        const link = h.querySelector('a');
        if (link) {
            navClick(link, headerHeight, h);
        }
        observer.observe(h);
    });
}

function navClick(link, headerHeight, head = null) {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        const href = link.getAttribute('href');
        if (!head) {
            head = document.getElementById(`${href.replace('#', '')}`);
        }
        const elementPosition = head.getBoundingClientRect().top + window.scrollY;
        const offsetPosition = elementPosition - headerHeight;
        history.pushState(null, '', href);
        window.scrollTo({
            top: offsetPosition - 20,
            behavior: 'smooth'
        });
    });
}

initFontSize();
function init() {
    initReadMode();
    initNavLinks();
    initResize();
    initSearch().then(() => {

    });
    const resizeObserver = new SizeObserver();
    resizeObserver.init();
    console.timeEnd('load');
}


if (typeof Turbo !== 'undefined') {
    document.addEventListener('turbo:load', init);
} else {
    document.addEventListener('DOMContentLoaded', init);
}

// alert('Поправить Анимацию сжимания и разжимания контейнера для турбо');
function getInitialState(name) {
    const savedState = localStorage.getItem(name);
    return savedState ? savedState === 'true' : false;
}

function initFontSize() {
    let state = localStorage.getItem('sf-fontSize');
    if (state) {
        state = JSON.parse(state);
        document.documentElement.style.fontSize = state.size;
    }
    window.addEventListener('Radio:render', (event) => {
        const {detail} = event;
        if (detail) {
            switch (detail.id) {
                case 'size_switch':
                    const inputs = detail.querySelectorAll('input');
                    inputs && inputs.forEach((input,index) => {
                        const size = index === 0 ? '14px' : index === 1 ? '16px' : '18px';
                        if(state && state.index === index) {
                            input.checked = true;
                        }
                        input.addEventListener('change', () => {
                            document.documentElement.style.fontSize = size;
                            localStorage.setItem('sf-fontSize', JSON.stringify({
                                index: index,
                                size: size
                            }));
                        });
                    });
                    break;
                default:
                    break;
            }
        }
    });
}

function initReadMode() {
    const state = getInitialState('readMode');
    if (state) {
        const button = document.getElementById('read_mode');
        if (button) {
            readMode(button);
        }
    }
}


function initResize() {
    const body = document.querySelector('body');

    const readState = getInitialState('readMode');
    const isExpanded = getInitialState('expanded');

    if (isExpanded && !readState) {
        setResize(body, isExpanded);
    }

}

function getNextOrPrevHiddenItem(container, items, next) {
    const containerRect = container.getBoundingClientRect();
    const active = container.querySelector('.active');

    if (!items.length) {
        return null;
    }
    if (active) {
        active.classList.remove('active');
        const activeIndex = items.indexOf(active);
        if (next) {
            items = items.slice(activeIndex + 1);
        } else {
            items = items.slice(0, activeIndex - 1).reverse();
        }
    }

    for (const item of items) {
        const rect = item.getBoundingClientRect();
        let isFullyVisible;
        if (next) {
            isFullyVisible = rect.left >= containerRect.left && rect.right <= containerRect.right;
        } else {
            isFullyVisible = rect.right <= containerRect.right && rect.left >= containerRect.left;
        }

        if (!isFullyVisible) {
            return item;
        }
    }

    return null;
}

window.menuScroll = function (button, next = true) {
    const menu = document.getElementById('top_menu');
    const container = document.querySelector('.sf-menu-container');
    const items = Array.from(menu.children);
    const nextHidden = getNextOrPrevHiddenItem(menu, items, next);
    const nextClass = next ? 'left' : 'right';
    if (menu) {
        if (nextHidden) {
            const index = items.indexOf(nextHidden);
            const needIndex = next ? index + 1 : index - 1;
            menu.scrollTo({
                left: next ? nextHidden.offsetLeft + nextHidden.clientWidth : nextHidden.offsetLeft - nextHidden.clientWidth,
                behavior: 'smooth'
            });
            nextHidden.classList.add('active');
            if (!items[needIndex]) {
                if (next) {
                    container.classList.remove('p-right-5');
                    container.classList.add('p-left-5');
                } else {
                    container.classList.remove('p-left-5');
                    container.classList.add('p-right-5');
                }
                const nextButton = document.querySelector(`.sf-menu-scroll.${nextClass}`);
                if (nextButton) {
                    nextButton.classList.remove('hidden');
                }
                button.classList.add('hidden');
            }
        }
    }
};

function setResize(container, isExpanded) {
    if (isExpanded) {
        container.classList.add('max-container-8');
        container.classList.remove('max-container-6');
    } else {
        container.classList.add('max-container-6');
        container.classList.remove('max-container-8');
    }
    localStorage.setItem('expanded', isExpanded.toString());
}

window.toggleResize = function () {
    const isExpanded = !getInitialState('expanded'),
        body = document.querySelector('body');
    setResize(body, isExpanded);
};


window.readMode = function (button) {
    const icon = button.children[0];
    const header = document.querySelector('header');
    const body = document.body;
    const navMenu = document.querySelector('.sf-nav-menu--left');
    const sideMenu = document.getElementById('side_menu');
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
    });

    if (icon) {
        if (mainContainer.classList.contains('read')) {
            icon.textContent = 'fullscreen_exit';
            body.classList.remove('max-container-8', 'max-container-6');
            body.classList.add('max-container-1', 'read');
            setReadModePosition(mainContainer, sideMenu);
        } else {
            icon.textContent = 'fullscreen';
            body.classList.remove('max-container-1', 'read');
            const isExpanded = getInitialState('expanded');
            body.classList.add(`max-container-${isExpanded ? '8' : '6'}`);
            sideMenu.removeAttribute('style');
        }
    }
    localStorage.setItem('readMode', String(mainContainer.classList.contains('read')));

};


window.navOpen = function () {
    const nav = document.getElementById('side_menu');
    const mainMenu = document.getElementById('main_menu');
    nav && nav.classList.toggle('active');
    if (nav.classList.contains('active')) {
        document.body.classList.add('overflow-hidden');
    } else {
        document.body.classList.remove('overflow-hidden');
    }
    mainMenu && mainMenu.classList.remove('active');
};

window.langOpen = function (item) {

    const language_switch_panel = item.parentElement.querySelector('.sf-language-switch--language-panel');
    if (language_switch_panel.classList.contains("sf-language-switch--language-panel-show"))
        language_switch_panel.classList.remove("sf-language-switch--language-panel-show");
    else
        language_switch_panel.classList.add("sf-language-switch--language-panel-show");

};

window.langSwitch = function (button) {
    const newLocale = button.dataset.locale;
    if (newLocale !== locale) {
        document.cookie = `locale=${newLocale}; path=/; max-age=31536000`; // 1 year
        const currentPath = window.location.pathname.split('/');
        const currentLocale = locale;
        window.location.href = currentPath.map((segment) =>
            segment === currentLocale ? newLocale : segment
        ).join('/');
    }
};

window.addEventListener('click', function (e) {
    const langContainer = document.querySelector('.sf-language-switch--container');
    const menu = langContainer.querySelector('.sf-language-switch--language-panel-show');
    const settingsContainer = document.querySelector('.sf-settings-wrap');
    if (!menu && !settingsContainer) {
        return false;
    }
    if(e.target !== settingsContainer && !settingsContainer.contains(e.target)) {
        settingsContainer.classList.remove('active')
    }
    if (e.target !== langContainer && !langContainer.contains(e.target)) {
        document.querySelector('.sf-language-switch--language-panel').classList.remove("sf-language-switch--language-panel-show");
    }
});


