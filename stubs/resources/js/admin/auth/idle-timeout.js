import { ModalController } from '../utils/modal-controller';

class IdleTimeoutManager {
    constructor() {
        const enabledMeta = document.querySelector('meta[name="idle-timeout-enabled"]');
        const minutesMeta = document.querySelector('meta[name="idle-timeout-minutes"]');

        this.enabled = enabledMeta ? enabledMeta.content === '1' || enabledMeta.content === 'true' : false;
        this.timeoutMinutes = minutesMeta ? parseFloat(minutesMeta.content) : 1;

        if (!this.enabled || isNaN(this.timeoutMinutes) || this.timeoutMinutes <= 0) {
            return;
        }

        this.idleThreshold = this.timeoutMinutes * 60 * 1000;
        this.warningDuration = 60 * 1000;

        this.lastActivity = Date.now();
        this.lastBroadcast = 0;
        this.logoutTime = null;
        this.isModalOpen = false;

        this.modal = null;
        this.keepAliveBtn = document.getElementById('btn-keep-alive');
        this.logoutForm = document.getElementById('form-idle-logout');

        this.channel = new BroadcastChannel('ksa-idle-sync');

        this.init();
    }

    init() {
        this.modal = new ModalController('idle-modal', {
            backdropId: 'idle-modal-backdrop',
            onClose: () => {
                if (this.isModalOpen) {
                    this.keepAlive();
                }
            }
        });

        this.channel.postMessage({ type: 'requestSync' });

        this.bindEvents();
        this.startTracking();
    }

    bindEvents() {
        const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
        events.forEach(evt => {
            document.addEventListener(evt, () => this.recordActivity(), { passive: true });
        });

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.checkIdleState();
            }
        });

        if (this.keepAliveBtn) {
            this.keepAliveBtn.addEventListener('click', () => this.keepAlive());
        }

        this.channel.onmessage = (event) => this.handleChannelMessage(event);
    }

    handleChannelMessage(event) {
        const data = event.data;
        switch (data.type) {
            case 'activity':
                if (data.timestamp > this.lastActivity) {
                    this.lastActivity = data.timestamp;
                    if (this.isModalOpen) {
                        this.closeModalLocal();
                    }
                }
                break;
            case 'showWarning':
                if (!this.isModalOpen) {
                    this.logoutTime = data.logoutTime;
                    this.showWarningLocal();
                }
                break;
            case 'keepAlive':
                this.closeModalLocal();
                this.lastActivity = data.timestamp;
                break;
            case 'forceLogout':
                setTimeout(() => { window.location.reload(); }, 1500);
                break;
            case 'requestSync':
                this.channel.postMessage({ type: 'activity', timestamp: this.lastActivity });
                if (this.isModalOpen && this.logoutTime) {
                    this.channel.postMessage({ type: 'showWarning', logoutTime: this.logoutTime });
                }
                break;
        }
    }

    recordActivity() {
        if (!this.isModalOpen) {
            const now = Date.now();
            this.lastActivity = now;
            if (now - this.lastBroadcast > 2000) {
                this.channel.postMessage({ type: 'activity', timestamp: this.lastActivity });
                this.lastBroadcast = now;
            }
        }
    }

    startTracking() {
        setInterval(() => this.checkIdleState(), 1000);
        setInterval(() => this.syncBackendActivity(), 300000);
    }

    async syncBackendActivity() {
        if (this.isModalOpen) return;

        const now = Date.now();
        if (now - this.lastActivity <= 300000) {
            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const token = csrfMeta ? csrfMeta.content : '';

                await fetch('/portal-ksa/keep-alive', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
            }
        }
    }

    checkIdleState() {
        const now = Date.now();

        if (this.isModalOpen) {
            if (this.logoutTime) {
                const remainingSecs = Math.max(0, Math.ceil((this.logoutTime - now) / 1000));

                const logoutBtn = document.getElementById('btn-idle-logout');
                if (logoutBtn) {
                    logoutBtn.textContent = `Logout (${remainingSecs}s)`;
                }

                if (remainingSecs <= 0) {
                    this.forceLogout(true);
                }
            }
        } else {
            const idleTime = now - this.lastActivity;
            if (idleTime >= this.idleThreshold) {
                this.showWarning();
            }
        }
    }

    showWarning() {
        this.logoutTime = Date.now() + this.warningDuration;
        this.channel.postMessage({ type: 'showWarning', logoutTime: this.logoutTime });
        this.showWarningLocal();
    }

    showWarningLocal() {
        this.isModalOpen = true;
        const logoutBtn = document.getElementById('btn-idle-logout');
        if (logoutBtn) {
            logoutBtn.textContent = `Logout (60s)`;
        }
        this.modal.open();
    }

    closeModalLocal() {
        this.isModalOpen = false;
        this.logoutTime = null;

        const logoutBtn = document.getElementById('btn-idle-logout');
        if (logoutBtn) {
            logoutBtn.textContent = 'Logout';
        }

        if (this.modal && this.modal.isOpen()) {
            this.modal.close();
        }
    }

    async forceLogout(isPrimary = false) {
        if (isPrimary && this.logoutForm) {
            this.channel.postMessage({ type: 'forceLogout' });

            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const token = csrfMeta ? csrfMeta.content : '';

                const response = await fetch('/portal-ksa/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'is_idle=1'
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.redirect) {
                        window.location.href = data.redirect + '&idle=1';
                        return;
                    }
                }

                window.location.href = '/portal-ksa/login?logged_out=1&idle=1';
            } catch (error) {
                window.location.href = '/portal-ksa/login?logged_out=1&idle=1';
            }
        } else {
            window.location.reload();
        }
    }

    async keepAlive() {
        this.closeModalLocal();
        this.lastActivity = Date.now();
        this.channel.postMessage({ type: 'keepAlive', timestamp: this.lastActivity });

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.content : '';

            await fetch('/portal-ksa/keep-alive', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Failed to keep session alive:', error);
        }
    }
}

export function initIdleTimeout() {
    new IdleTimeoutManager();
}
