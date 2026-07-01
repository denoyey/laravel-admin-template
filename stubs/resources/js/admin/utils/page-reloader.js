/**
 * PageReloader - A reusable utility class for managing page reloads.
 *
 * Centralizes all `window.location.reload()` calls into a single class,
 * making it easier to maintain, extend with logging/analytics, and test.
 *
 * Usage:
 *   import { PageReloader } from '../utils/page-reloader';
 *   PageReloader.reload();                       // Immediate reload
 *   PageReloader.reloadWithDelay(3000);           // Delayed reload (3s)
 *   PageReloader.reloadToUrl('/portal-admin/login'); // Redirect to specific URL
 */
class PageReloader {
    /**
     * Perform an immediate full page reload.
     * Forces the browser to fetch the page from the server,
     * bypassing any cached version.
     */
    static reload() {
        window.location.reload();
    }

    /**
     * Perform a page reload after a specified delay.
     *
     * @param {number} delayMs - Delay in milliseconds before reload.
     * @returns {number} The timeout ID (can be used with clearTimeout to cancel).
     */
    static reloadWithDelay(delayMs) {
        return setTimeout(() => {
            PageReloader.reload();
        }, delayMs);
    }

    /**
     * Navigate the browser to a specific URL.
     * Useful for forced redirects (e.g., to login page).
     *
     * @param {string} url - The target URL to navigate to.
     */
    static reloadToUrl(url) {
        window.location.href = url;
    }
}

export { PageReloader };
