import { DomHelper } from './dom-helper';

/**
 * ModalController - A reusable utility class for managing modal open/close behavior.
 *
 * Encapsulates the common modal pattern used across the application:
 * - Open/close via CSS class toggle (`is-open`)
 * - Backdrop click to close
 * - Escape key to close
 * - Optional callback on close (e.g., reset form)
 *
 * Usage:
 *   import { ModalController } from '../utils/modal-controller';
 *   const modal = new ModalController('delete-modal', {
 *       backdropId: 'delete-modal-backdrop',
 *       cancelBtnId: 'btn-cancel-delete',
 *       enableEscape: true,
 *       onClose: () => { console.log('closed!'); }
 *   });
 *   modal.open();
 *   modal.close();
 */
class ModalController {
    /**
     * @param {string} modalId - The ID of the modal container element.
     * @param {Object} options - Configuration options.
     * @param {string} [options.backdropId] - The ID of the backdrop element.
     * @param {string} [options.cancelBtnId] - The ID of the cancel/close button.
     * @param {boolean} [options.enableEscape=true] - Whether pressing Escape closes the modal.
     * @param {Function} [options.onClose] - Optional callback invoked after the modal closes.
     * @param {string} [options.openClass='is-open'] - CSS class used to show the modal.
     */
    constructor(modalId, options = {}) {
        this.modal = DomHelper.id(modalId);
        this.backdrop = options.backdropId ? DomHelper.id(options.backdropId) : null;
        this.cancelBtn = options.cancelBtnId ? DomHelper.id(options.cancelBtnId) : null;
        this.enableEscape = options.enableEscape !== undefined ? options.enableEscape : true;
        this.onClose = options.onClose || null;
        this.openClass = options.openClass || 'is-open';

        this.close = this.close.bind(this);
        this.handleKeyDown = this.handleKeyDown.bind(this);

        this.bindEvents();
    }

    /**
     * Bind click and keyboard events for closing the modal.
     */
    bindEvents() {
        if (!this.modal) return;

        if (this.backdrop) {
            this.backdrop.addEventListener('click', this.close);
        }

        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', this.close);
        }

        if (this.enableEscape) {
            document.addEventListener('keydown', this.handleKeyDown);
        }
    }

    /**
     * Handle Escape key press to close the modal.
     *
     * @param {KeyboardEvent} event
     */
    handleKeyDown(event) {
        if (event.key === 'Escape' && this.isOpen()) {
            this.close();
        }
    }

    /**
     * Check if the modal is currently open.
     *
     * @returns {boolean}
     */
    isOpen() {
        return this.modal ? this.modal.classList.contains(this.openClass) : false;
    }

    /**
     * Open the modal by adding the open class.
     */
    open() {
        if (this.modal) {
            this.modal.classList.add(this.openClass);


            setTimeout(() => {
                const focusEl = this.modal.querySelector('[autofocus]')
                             || this.modal.querySelector('[data-autofocus]')
                             || this.modal.querySelector('button[type="submit"]')
                             || this.modal.querySelector('.btn-primary')
                             || this.modal.querySelector('form button:not([type="button"])');
                if (focusEl) {
                    focusEl.focus();
                }
            }, 50);
        }
    }

    /**
     * Close the modal by removing the open class.
     * Invokes the onClose callback if provided.
     */
    close() {
        if (this.modal) {
            this.modal.classList.remove(this.openClass);

            if (this.onClose) {
                setTimeout(this.onClose, 300);
            }
        }
    }
}

export { ModalController };
