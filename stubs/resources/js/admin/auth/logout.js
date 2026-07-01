import { DomHelper } from '../utils/dom-helper';
import { ModalController } from '../utils/modal-controller';

class LogoutManager {
    constructor() {
        this.modalController = new ModalController('logout-modal', {
            backdropId: 'logout-modal-backdrop',
            cancelBtnId: 'btn-cancel-logout',
            enableEscape: true,
        });

        this.triggers = DomHelper.selectAll('.trigger-logout');
    }

    init() {
        if (!this.modalController.modal) return;

        this.triggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeDropdowns();
                this.modalController.open();
            });
        });
    }

    closeDropdowns() {
        const userMenu = DomHelper.id('user-dropdown-menu');
        const userBackdrop = DomHelper.id('user-backdrop');
        if (userMenu) userMenu.classList.add('hidden');
        if (userBackdrop) userBackdrop.classList.add('hidden');
    }
}

export function initAdminLogout() {
    const logoutManager = new LogoutManager();
    logoutManager.init();
}
