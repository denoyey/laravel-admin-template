import { DomHelper } from '../utils/dom-helper';

export function showAdminLoader() {
    let loader = DomHelper.id('page-loader');

    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.className = 'fixed inset-0 z-9999 flex items-center justify-center bg-white';
        loader.setAttribute('aria-hidden', 'true');
        loader.innerHTML = '<div id="loader-spinner" class="loader"></div>';
        document.body.prepend(loader);
    }

    loader.style.transition = 'none';
    loader.style.opacity = '1';
    loader.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

export function hideAdminLoader() {
    const loader = DomHelper.id('page-loader');
    if (!loader) return;

    document.body.style.overflow = '';

    loader.style.transition = 'opacity 0.4s ease-out';
    loader.style.opacity = '0';

    loader.addEventListener('transitionend', () => {
        if(loader.parentNode) loader.remove();
    }, { once: true });
}

export function initAdminLoading() {
    const loader = DomHelper.id('page-loader');
    if (loader) {
        document.body.style.overflow = 'hidden';
    }

    if (document.readyState === 'complete') {
        hideAdminLoader();
    } else {
        window.addEventListener('load', hideAdminLoader);
    }
}
