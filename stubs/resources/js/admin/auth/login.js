import { DomHelper } from '../utils/dom-helper';
import { StorageKeys } from '../utils/storage-keys';
import { PageReloader } from '../utils/page-reloader';
import { showAdminLoader } from '../layout/loading';

class LoginManager {
    constructor() {
        this.loginForm = DomHelper.select('form[action*="login"]');
        this.togglePassword = DomHelper.id('togglePassword');
        this.passwordInput = DomHelper.id('password');
        this.eyeIcon = DomHelper.id('eyeIcon');
        this.eyeSlashIcon = DomHelper.id('eyeSlashIcon');
    }

    init() {
        if (!this.loginForm) return;

        this.handleLoggedOutFlag();
        this.listenForRateLimitSync();
        this.initPasswordToggle();
        this.initFormSubmit();
    }

    handleLoggedOutFlag() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('logged_out')) {
            localStorage.setItem(StorageKeys.LOGOUT_TRIGGER, Date.now().toString());
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

    listenForRateLimitSync() {
        window.addEventListener('storage', (e) => {
            if (e.key === StorageKeys.RATE_LIMITED && e.newValue) {
                PageReloader.reload();
            }
        });
    }

    initPasswordToggle() {
        if (!this.togglePassword || !this.passwordInput) return;

        this.togglePassword.addEventListener('click', () => {
            const isPassword = this.passwordInput.getAttribute('type') === 'password';

            this.passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            if (isPassword) {
                if (this.eyeIcon) this.eyeIcon.classList.add('hidden');
                if (this.eyeSlashIcon) this.eyeSlashIcon.classList.remove('hidden');
            } else {
                if (this.eyeIcon) this.eyeIcon.classList.remove('hidden');
                if (this.eyeSlashIcon) this.eyeSlashIcon.classList.add('hidden');
            }
        });
    }

    initFormSubmit() {
        this.loginForm.addEventListener('submit', () => {
            showAdminLoader();
        });
    }
}

export function initAdminLogin() {
    const loginManager = new LoginManager();
    loginManager.init();
}
