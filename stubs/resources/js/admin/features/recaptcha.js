import { DomHelper } from '../utils/dom-helper';

class RecaptchaManager {
    constructor() {
        this.recaptchaElements = DomHelper.selectAll('.g-recaptcha');
        this.init();
    }

    init() {
        if (!this.recaptchaElements.length) return;

        const existingScript = document.querySelector('script[src*="recaptcha/api.js"]');
        if (!existingScript) {
            const script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }
    }
}

export function initRecaptcha() {
    new RecaptchaManager();
}
