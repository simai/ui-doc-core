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
        this.body = document.querySelector('body');
        this.main = document.querySelector('main');
        this.editButton = document.querySelector('.sf-button-edit');
        this.sidePanel = document.getElementById('side_panel');
        this.readMode = document.getElementById('read_mode');
        this.sideMenu = document.getElementById('side_menu');
        this.navMenu = document.getElementById('main_menu');
        this.logo = this.headerWrap.querySelector('a.logo');
        this.setObserver();
    }

    setObserver() {
        this.menuObserver = new ResizeObserver(entries => {
            for (const entry of entries) {
                const width = entry.contentRect.width;
                if (width < 543) {
                    if (this.setCollapsed) return;
                    this.menu.classList.add('menu--collapsed',  'p-right-5');
                    this.setCollapsed = true;
                } else {
                    if (!this.setCollapsed) return;
                    this.menu.classList.remove('menu--collapsed', 'p-right-5');
                    this.setCollapsed = false;
                }
            }
        });
        this.mainObserver = new ResizeObserver(entries => {
            for (const entry of entries) {
                const width = entry.contentRect.width;
                if (width === this.lastWidth) return;
                this.lastWidth = width;
                if(width < 980 && width > 768) {
                    if (!this.setTabletBug) {
                        this.setTabletBug = true;
                        this.body.append(this.readMode);
                    }
                    if (this.setTopMenu) {
                        this.setTopMenu = false;
                        this.logo.after(this.menu);
                    }
                } else if (width <= 768) {
                    if (this.main.classList.contains('read')) {
                        readMode(this.readMode);
                    }
                    if (!this.setTabletBug) {
                        this.setTabletBug = true;
                    }
                    if (!this.setTopMenu) {
                        this.setTopMenu = true;
                        this.navMenu.prepend(this.menu);
                    }
                } else {
                    if (this.main.classList.contains('read')) {
                        const menuOffset = this.main.getBoundingClientRect().left + this.main.clientWidth + this.sideMenu.clientWidth + 16;
                        if (menuOffset >= width) {
                            this.setReadInside = true;
                        } else {
                            this.setReadInside = false;
                        }
                        setReadModePosition(this.main, this.sideMenu, this.setReadInside);
                    }
                    document.body.classList.remove('overflow-hidden');

                    if (this.setTabletBug) {
                        this.sidePanel.prepend(this.readMode);
                        this.setTabletBug = false;
                    }
                    if (this.setTopMenu) {
                        this.setTopMenu = false;
                        this.logo.after(this.menu);
                    }
                }
            }
        });
    }

    init() {
        this.mainObserver.observe(this.body);
        this.menuObserver.observe(this.menu);
    }
}
