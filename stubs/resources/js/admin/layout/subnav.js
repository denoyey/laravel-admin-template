class AdminSubnavManager {
    constructor() {
        this.subnav = document.getElementById('admin-subnav');
        this.breakpointLg = 1024;
        this.activeClassSelector = 'a.bg-hijau\\/10';

        if (this.subnav) {
            this.init();
        }
    }

    init() {
        this.scrollToActiveTab();
    }

    scrollToActiveTab() {
        if (window.innerWidth < this.breakpointLg) {
            const activeTab = this.subnav.querySelector(this.activeClassSelector);

            if (activeTab) {
                const scrollPos = activeTab.offsetLeft - (this.subnav.clientWidth / 2) + (activeTab.clientWidth / 2);
                this.subnav.scrollTo({ left: scrollPos, behavior: 'smooth' });
            }
        }
    }
}

export function initAdminSubnav() {
    new AdminSubnavManager();
}
