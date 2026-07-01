import { DomHelper } from '../utils/dom-helper';

class ToastNotification {
    constructor() {
        this.container = DomHelper.id('toast-container');
        this.toasts = DomHelper.selectAll('.toast-alert');
        this.duration = 5000;
        this.init();
    }

    init() {
        if (!this.toasts.length) return;

        this.toasts.forEach(toast => {
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'scale-95');
                toast.classList.add('opacity-100', 'scale-100');
            });

            const closeBtn = DomHelper.select('.toast-close', toast);
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    this.hideToast(toast);
                });
            }

            setTimeout(() => {
                this.hideToast(toast);
            }, this.duration);
        });
    }

    hideToast(toast) {
        toast.classList.remove('opacity-100', 'scale-100');
        toast.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

export function showToast(type, title, message) {
    const container = DomHelper.id('toast-container');
    if (!container) return;

    const existingToasts = container.querySelectorAll('.toast-alert');
    for (let i = 0; i < existingToasts.length; i++) {
        const existingMessage = existingToasts[i].querySelector('p')?.innerText;
        const existingTitle = existingToasts[i].querySelector('h4')?.innerText;
        if (existingMessage === message && existingTitle === title) {
            return;
        }
    }

    if (existingToasts.length >= 3) {
        const oldestToast = existingToasts[0];
        oldestToast.classList.remove('opacity-100', 'scale-100');
        oldestToast.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            if (oldestToast.parentNode) {
                oldestToast.parentNode.removeChild(oldestToast);
            }
        }, 300);
    }

    let iconHtml = '';
    let colorClass = '';
    let bgIconClass = '';
    let borderClass = '';

    if (type === 'success') {
        colorClass = 'text-hijau-dark';
        bgIconClass = 'bg-hijau/10';
        borderClass = 'border-hijau/20';
        iconHtml = `<svg class="w-4 h-4 text-hijau" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
    } else {
        colorClass = 'text-red-700';
        bgIconClass = 'bg-red-50 border border-red-100';
        borderClass = 'border-red-100';
        iconHtml = `<svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
    }

    const toastHtml = `
        <div class="toast-alert flex items-start gap-3 p-3.5 bg-white border ${borderClass} rounded-md shadow-[0_4px_12px_rgba(0,0,0,0.05)] mb-3 w-max min-w-[280px] sm:min-w-[320px] max-w-[90vw] sm:max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95 relative overflow-hidden group">
            <div class="shrink-0 w-7 h-7 rounded-full ${bgIconClass} flex items-center justify-center mt-0.5 shadow-sm">
                ${iconHtml}
            </div>
            <div class="flex-1 min-w-0 pr-2">
                <h4 class="text-[12.5px] font-semibold text-gray-800 leading-snug">${title}</h4>
                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">${message}</p>
            </div>
            <button type="button" class="toast-close shrink-0 p-1 -m-1 text-gray-400 hover:text-gray-600 focus:outline-none rounded-md hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    const newToast = container.lastElementChild;

    requestAnimationFrame(() => {
        newToast.classList.remove('opacity-0', 'scale-95');
        newToast.classList.add('opacity-100', 'scale-100');
    });

    const closeBtn = DomHelper.select('.toast-close', newToast);
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            newToast.classList.remove('opacity-100', 'scale-100');
            newToast.classList.add('opacity-0', 'scale-95');
            setTimeout(() => newToast.remove(), 300);
        });
    }

    setTimeout(() => {
        if (newToast.parentNode) {
            newToast.classList.remove('opacity-100', 'scale-100');
            newToast.classList.add('opacity-0', 'scale-95');
            setTimeout(() => newToast.remove(), 300);
        }
    }, 5000);
}

window.showToast = showToast;

export function initToastNotification() {
    new ToastNotification();
}
