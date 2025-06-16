export class SizeObserver {
    constructor() {
        this.lastWidth = null;
        this.setTabletBug = false;
        this.setTopMenu = false;
        this.setCollapsed = false;
        this.menu = document.querySelector('.sf-menu-container');
        this.headerWrap = document.querySelector('.header--wrap');
        this.body = document.querySelector('body');
        this.bug = document.getElementById('sf_bug');
        this.sidePanel = document.getElementById('side_panel');
        this.topMenuContainer = document.querySelector('.sf-menu-container');
        this.navMenu = document.getElementById('main_menu');
        this.logo = this.headerWrap.querySelector('a.logo');
        this.setObserver();
    }

    setObserver() {
        this.menuObserver = new ResizeObserver(entries => {
            for (let entry of entries) {
                const width = entry.contentRect.width;
                if (width < 543) {
                    if(this.setCollapsed) return;
                    this.menu.classList.add('menu--collapsed', 'overflow-hidden', 'p-right-5');
                    this.setCollapsed = true;
                } else {
                    if(!this.setCollapsed) return;
                    this.menu.classList.remove('menu--collapsed', 'overflow-hidden', 'p-right-5');
                    this.setCollapsed = false;
                }
            }
        });
        this.mainObserver = new ResizeObserver(entries => {
            for (let entry of entries) {
                const width = entry.contentRect.width;
                if (width === this.lastWidth) return;
                this.lastWidth = width;
                if (width < 768) {
                    if (!this.setTabletBug) {
                        this.setTabletBug = true;
                        this.body.append(this.bug);
                    }
                    if(!this.setTopMenu) {
                        this.setTopMenu = true;
                        this.navMenu.prepend(this.topMenuContainer)
                    }
                } else {

                    document.body.classList.remove('overflow-hidden');

                    if (this.setTabletBug) {
                        this.setTabletBug = false;
                        this.sidePanel.append(this.bug);
                    }
                    if(this.setTopMenu) {
                        this.setTopMenu = false;
                        this.logo.after(this.topMenuContainer);
                    }
                }
            }
        });
    }

    init() {
        this.mainObserver.observe(this.body);
        this.menuObserver.observe(this.topMenuContainer)
    }
}
