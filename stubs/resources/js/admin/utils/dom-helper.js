/**
 * DomHelper - A reusable utility class for DOM element operations.
 *
 * Centralizes all `document.getElementById`, `document.querySelector`,
 * and `document.querySelectorAll` calls into a single class with
 * consistent error handling and shorter syntax.
 *
 * Usage:
 *   import { DomHelper } from '../utils/dom-helper';
 *   const el = DomHelper.id('my-element');
 *   const el = DomHelper.select('.my-class');
 *   const els = DomHelper.selectAll('.my-class');
 */
class DomHelper {
    /**
     * Get a single element by its ID.
     *
     * @param {string} id - The element ID (without #).
     * @returns {HTMLElement|null} The found element or null.
     */
    static id(id) {
        return document.getElementById(id);
    }

    /**
     * Get a single element using a CSS selector.
     *
     * @param {string} selector - A valid CSS selector string.
     * @param {HTMLElement|Document} parent - The parent to search within (defaults to document).
     * @returns {HTMLElement|null} The first matching element or null.
     */
    static select(selector, parent = document) {
        return parent.querySelector(selector);
    }

    /**
     * Get all elements matching a CSS selector.
     *
     * @param {string} selector - A valid CSS selector string.
     * @param {HTMLElement|Document} parent - The parent to search within (defaults to document).
     * @returns {NodeList} A NodeList of matching elements.
     */
    static selectAll(selector, parent = document) {
        return parent.querySelectorAll(selector);
    }
}

export { DomHelper };
