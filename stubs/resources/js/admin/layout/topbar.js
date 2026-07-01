import { DomHelper } from '../utils/dom-helper';

class AdminTopbar {
    constructor() {
        this.dropdowns = [];
    }

    init() {
        this.setupDropdown('user-dropdown-btn', 'user-dropdown-menu', 'user-backdrop');
        this.setupDropdown('notification-dropdown-btn', 'notification-dropdown-menu', 'notification-backdrop');
    }

    setupDropdown(btnId, menuId, backdropId = null) {
        const btn = DomHelper.id(btnId);
        const menu = DomHelper.id(menuId);
        const backdrop = backdropId ? DomHelper.id(backdropId) : null;

        if (!btn || !menu) return;

        this.dropdowns.push({ btn, menu, backdrop });

        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            this.dropdowns.forEach(dropdown => {
                if (dropdown.menu !== menu) {
                    dropdown.menu.classList.add('hidden');
                    if (dropdown.backdrop) dropdown.backdrop.classList.add('hidden');
                }
            });

            menu.classList.toggle('hidden');
            if (backdrop) backdrop.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('hidden');
                if (backdrop) backdrop.classList.add('hidden');
            }
        });
    }
}

export function initAdminTopbar() {
    const topbar = new AdminTopbar();
    topbar.init();
}
