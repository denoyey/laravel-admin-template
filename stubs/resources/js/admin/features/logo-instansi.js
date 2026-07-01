/**
 * Logo Instansi Manager
 *
 * Manages the logo instansi page interactions:
 * - Edit modal for existing logos
 *
 * Expects data-attributes on #logo-instansi-section for dynamic values.
 */

import { ModalController } from '../utils/modal-controller';

class LogoInstansiEditModalManager {
    constructor() {
        this.modal = document.getElementById('editLogoModal');
        if (!this.modal) return;

        this.backdrop = document.getElementById('editLogoModalBackdrop');
        this.content = document.getElementById('editLogoModalContent');
        this.form = document.getElementById('editLogoForm');
        this.altInput = document.getElementById('edit_img_alt_logo_instansi');
        this.fileInput = document.getElementById('edit_img_logo_instansi');
        this.baseRoute = this.modal.dataset.baseRoute || '';

        this.bindEvents();
    }

    bindEvents() {
        document.querySelectorAll('.btn-edit-logo').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.logoId;
                const altText = btn.dataset.altText;
                const imgSrc = btn.dataset.imgSrc;
                this.open(id, altText, imgSrc);
            });
        });

        this.modal.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', () => this.close());
        });

        this.backdrop.addEventListener('click', () => this.close());
    }

    open(id, altText, imgSrc) {
        this.form.action = `${this.baseRoute}/${id}`;
        this.altInput.value = altText || '';

        const previewContainer = document.getElementById('edit_img_logo_instansi-preview');
        if (previewContainer) {
            const img = previewContainer.querySelector('.preview-img');
            const placeholder = previewContainer.querySelector('.preview-placeholder');
            const editOverlays = previewContainer.querySelectorAll('.edit-overlay');

            if (img) {
                img.src = imgSrc;
                img.setAttribute('data-original-src', imgSrc);
                img.classList.remove('hidden');
                img.classList.add('block');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
                placeholder.classList.remove('flex');
            }
            if (editOverlays) {
                editOverlays.forEach(overlay => {
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                });
            }
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('block');
        }

        this.modal.classList.remove('hidden');
        void this.modal.offsetWidth;

        this.backdrop.classList.remove('opacity-0');
        this.content.classList.remove('scale-95', 'opacity-0');
        this.content.classList.add('scale-100', 'opacity-100');
    }

    close() {
        this.backdrop.classList.add('opacity-0');
        this.content.classList.remove('scale-100', 'opacity-100');
        this.content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            this.modal.classList.add('hidden');
            if (this.fileInput) this.fileInput.value = '';
        }, 200);
    }
}

export function initLogoInstansi() {
    new LogoInstansiEditModalManager();
}
