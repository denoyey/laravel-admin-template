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
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.checkSessionValidity();
            }
        });
    }

    handleStorageChange(event) {
        if (!window.location.pathname.startsWith('/portal-admin')) return;

        if (event.key === StorageKeys.LOGIN_TRIGGER) {
            PageReloader.reload();
        } else if (event.key === StorageKeys.LOGOUT_TRIGGER) {
            if (!window.location.pathname.endsWith('/login')) {
                PageReloader.reload();
            }
        }
    }

    checkSessionValidity() {
        const path = window.location.pathname;
        if (!path.startsWith('/portal-admin')) return;
        if (path.endsWith('/login')) return;

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
    }

    startSessionPolling() {
        setInterval(() => this.checkSessionValidity(), this.pollingInterval);
    }
}

export function initSessionSync() {
    const sessionSync = new SessionSync();
    sessionSync.init();
}
