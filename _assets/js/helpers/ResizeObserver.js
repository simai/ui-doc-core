import setReadModePosition from "./functions";

export class SizeObserver {
    constructor() {
        this.lastWidth = null;
        this.setTabletBug = false;
        this.setTopMenu = false;
        this.setCollapsed = false;
        this.setReadInside = false;
        this.menu = document.querySelector('.sf-menu-container');

        this.headerWrap = document.querySelector('.header--wrap');
        this.headerRight = this.headerWrap.querySelector('.header--right');
        this.body = document.querySelector('body');
        this.main = document.querySelector('main');
        this.readMode = document.getElementById('read_mode');
        this.sideMenu = document.getElementById('side_menu');
        this.navMenu = document.getElementById('main_menu');
        this.logo = this.headerWrap.querySelector('a.logo');
        this.setObserver();
    }

    setObserver() {
        if (this.menu) {
            this.menuObserver = new ResizeObserver(entries => {
                for (const entry of entries) {
                    const width = entry.contentRect.width;
                    if (width < 543) {
                        if (this.setCollapsed) return;
                        this.menu.classList.add('menu--collapsed', 'p-right-5');
                        this.setCollapsed = true;
                    } else {
                        if (!this.setCollapsed) return;
                        this.menu.classList.remove('menu--collapsed', 'p-right-5');
                        this.setCollapsed = false;
                    }
                }
            });
        }
        this.mainObserver = new ResizeObserver(entries => {
            for (const entry of entries) {
                const width = entry.contentRect.width;
                if (width === this.lastWidth) return;
                this.lastWidth = width;
                if (width < 980 && width > 768) {
                    if (!this.setTabletBug) {
                        this.setTabletBug = true;
                        if(this.readMode) {
                            this.body.append(this.readMode);
                        }
                    }
                    if (this.setTopMenu && this.menu) {
                        this.setTopMenu = false;
                        this.logo.after(this.menu);
                    }
                } else if (width <= 768) {
                    if(this.readMode) {
                        if (this.main.classList.contains('read')) {
                            readMode(this.readMode);
                        }
                    }
                    if (!this.setTabletBug) {
                        this.setTabletBug = true;
                    }
                    if (!this.setTopMenu && this.menu) {
                        this.setTopMenu = true;
                        this.navMenu.prepend(this.menu);
                    }
                } else {
                    if (this.readMode && this.main.classList.contains('read')) {
                        setTimeout(() => {
                            const menuOffset = this.main.getBoundingClientRect().left + this.main.clientWidth + this.readMode.clientWidth + 16;
                            if (menuOffset >= width) {
                                this.setReadInside = true;
                            } else {
                                this.setReadInside = false;
                            }
                            this.readMode.classList.add('fixed');
                            setReadModePosition(this.main, this.readMode, this.setReadInside);
                        }, 200);
                    }
                    document.body.classList.remove('overflow-hidden');

                    if (this.setTabletBug) {
                        if(this.readMode) {
                            this.headerRight.prepend(this.readMode);
                        }
                        this.setTabletBug = false;
                    }
                    if (this.setTopMenu && this.menu) {
                        this.setTopMenu = false;
                        this.logo.after(this.menu);
                    }
                }
            }
        });
    }

    init() {
        this.mainObserver.observe(this.body);
        if (this.menu) {
            this.menuObserver.observe(this.menu);
        }

    }
}
