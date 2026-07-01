import { DomHelper } from '../utils/dom-helper';
import { StorageKeys } from '../utils/storage-keys';
import { PageReloader } from '../utils/page-reloader';

class SessionSync {
    constructor() {
        this.pollingInterval = 300000;
        this.handleStorageChange = this.handleStorageChange.bind(this);
    }

    init() {
        this.syncLoginFlag();
        this.listenForStorageChanges();
        this.startSessionPolling();
    }

    syncLoginFlag() {
        const loginFlag = DomHelper.id('login-success-flag');
        if (loginFlag && loginFlag.dataset.loginSuccess === 'true') {
            localStorage.setItem(StorageKeys.LOGIN_TRIGGER, Date.now().toString());
        }
    }

    listenForStorageChanges() {
        window.addEventListener('storage', this.handleStorageChange);
    }

    handleStorageChange(event) {
        if (!window.location.pathname.startsWith('/portal-ksa')) return;

        if (event.key === StorageKeys.LOGIN_TRIGGER) {
            PageReloader.reload();
        } else if (event.key === StorageKeys.LOGOUT_TRIGGER) {
            if (!window.location.pathname.endsWith('/login')) {
                PageReloader.reload();
            }
        }
    }

    startSessionPolling() {
        const path = window.location.pathname;
        if (!path.startsWith('/portal-ksa')) return;
        if (path.endsWith('/login')) return;

        setInterval(() => {
            fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-Session-Sync': '1'
                }
            }).then(response => {
                if (response.status === 401 || response.status === 403) {
                    PageReloader.reload();
                } else if (response.redirected && response.url.includes('/login')) {
                    PageReloader.reload();
                }
            }).catch(() => { });
        }, this.pollingInterval);
    }
}

export function initSessionSync() {
    const sessionSync = new SessionSync();
    sessionSync.init();
}
