import { DomHelper } from '../utils/dom-helper';
import { StorageKeys } from '../utils/storage-keys';
import { PageReloader } from '../utils/page-reloader';

class RateLimitHandler {
    constructor() {
        this.errorPage = DomHelper.id('page-error-429');
        this.countdownContainer = DomHelper.id('countdown-container');
        this.countdownTimer = DomHelper.id('countdown-timer');
        this.fallbackDelay = 60000;
    }

    init() {
        if (!this.errorPage) return;

        localStorage.setItem(StorageKeys.RATE_LIMITED, Date.now().toString());

        if (window.location.pathname.includes('login')) {
            this.startCountdown();
        }
    }

    startCountdown() {
        if (this.countdownContainer && this.countdownTimer) {
            this.countdownContainer.classList.remove('hidden');
            let timeLeft = parseInt(this.countdownTimer.getAttribute('data-time'), 10) || 60;

            const interval = setInterval(() => {
                timeLeft--;
                this.countdownTimer.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    this.countdownTimer.textContent = '0';
                    PageReloader.reload();
                }
            }, 1000);
        } else {
            PageReloader.reloadWithDelay(this.fallbackDelay);
        }
    }
}

export function initRateLimit() {
    const rateLimitHandler = new RateLimitHandler();
    rateLimitHandler.init();
}
