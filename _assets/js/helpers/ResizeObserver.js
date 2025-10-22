import setReadModePosition from "./functions";

export class SizeObserver {
    constructor() {
        this.lastWidth = null;
        this.setTopMenu = false;
        this.setCollapsed = false;
        this.setReadInside = false;
        this.menu = document.querySelector('.sf-menu-container');
        this.readState = false;
        this.headerWrap = document.querySelector('.header--wrap');
        this.body = document.querySelector('body');
        this.main = document.querySelector('main');
        this.readMode = document.getElementById('read_mode');
        this.navMenu = document.getElementById('main_menu');
        if (this.headerWrap) {
            this.headerRight = this.headerWrap.querySelector('.header--right');
            this.logo = this.headerWrap.querySelector('a.logo');
        }
        this.setObserver();
    }

    mutate = (fn) => requestAnimationFrame(fn);
    placeReadModeForWidth = (width) => {
        if (!this.readMode) return;
        if (width < 980) {
            this.mutate(() => {
                if (!this.readState) {
                    this.readState = true;
                }
                requestAnimationFrame(() => {
                    if (!this.main || !this.readMode) return;
                    setReadModePosition(this.main, this.readMode);
                });
            });
        } else {
            this.mutate(() => {
                if (this.readState) {
                    this.readState = false;
                }
                requestAnimationFrame(() => {
                    if (!this.main || !this.readMode) return;
                    setReadModePosition(this.main, this.readMode);
                });
            });
        }
    };
    setObserver = () => {
        if (this.menu) {
            this.menuObserver = new ResizeObserver(entries => {
                for (const entry of entries) {
                    const width = entry.contentRect.width;
                    if (width < 543) {
                        if (this.setCollapsed) continue;
                        this.mutate(() => {
                            this.menu.classList.add('menu--collapsed', 'p-right-5');
                        });
                        this.setCollapsed = true;
                    } else {
                        if (!this.setCollapsed) continue;
                        this.mutate(() => {
                            this.menu.classList.remove('menu--collapsed', 'p-right-5');
                        });
                        this.setCollapsed = false;
                    }
                }
            });
            this.menuObserver.observe(this.menu);
        }

        this.mainObserver = new ResizeObserver(entries => {
            for (const entry of entries) {
                const width = entry.contentRect.width | 0;
                if (width === this.lastWidth) continue;
                this.lastWidth = width;
                this.placeReadModeForWidth(width);

                if (width < 980 && width > 768) {
                    if (this.setTopMenu && this.menu) {
                        this.setTopMenu = false;
                        this.mutate(() => this.logo.after(this.menu));
                    }
                } else if (width <= 768) {
                    if (!this.setTopMenu && this.menu) {
                        this.setTopMenu = true;
                        this.mutate(() => this.navMenu.prepend(this.menu));
                    }
                } else {

                    if (this.setTopMenu && this.menu) {
                        this.setTopMenu = false;
                        this.mutate(() => this.logo.after(this.menu));
                    }
                }
            }
        });
    }

    init() {
        this.mainObserver.observe(this.body);
    }
}
