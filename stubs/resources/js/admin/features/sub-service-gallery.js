/**
 * Sub Service Gallery Manager
 *
 * Manages existing image gallery interactions on the sub-service edit page:
 * - Save alt text via AJAX
 * - Set cover image via AJAX
 * - Delete single image via delete modal
 * - Delete all images via delete modal
 *
 * Uses data-attributes on #gallery-section for dynamic values (CSRF, routes).
 */

class SubServiceGalleryManager {
    constructor() {
        this.gallerySection = document.getElementById('gallery-section');
        if (!this.gallerySection) return;

        this.csrfToken = this.gallerySection.dataset.csrfToken;
        this.baseUrl = this.gallerySection.dataset.baseUrl;
        this.subServiceId = this.gallerySection.dataset.subServiceId;

        this.bindEvents();
    }

    bindEvents() {

        this.gallerySection.querySelectorAll('.btn-save-alt').forEach(btn => {
            btn.addEventListener('click', () => {
                const imageId = btn.dataset.imageId;
                this.updateImage(imageId);
            });
        });

        this.gallerySection.querySelectorAll('.btn-set-cover').forEach(radio => {
            radio.addEventListener('change', () => {
                const imageId = radio.dataset.imageId;
                this.updateImage(imageId, true);
            });
        });

        this.gallerySection.querySelectorAll('.btn-delete-image').forEach(btn => {
            btn.addEventListener('click', () => {
                const imageId = btn.dataset.imageId;
                this.deleteImage(imageId);
            });
        });

        const deleteAllBtn = document.getElementById('btn-delete-all-images');
        if (deleteAllBtn) {
            deleteAllBtn.addEventListener('click', () => {
                this.deleteAllImages();
            });
        }

        this.setupAjaxDeleteInterceptor();
    }

    /**
     * Update an existing image's alt text or cover status via AJAX.
     */
    updateImage(imageId, isCover = false) {
        const altInput = document.getElementById(`alt-input-${imageId}`);
        const data = {
            _token: this.csrfToken,
            _method: 'PUT',
            alt_text: altInput ? altInput.value : ''
        };

        if (isCover) {
            data.is_cover = 1;
        }

        window.axios.post(`${this.baseUrl}/images/${imageId}`, data)
            .then(response => {
                if (response.data.success) {
                    if (isCover) {
                        window.location.reload();
                    } else {
                        this.showToast('Alt text berhasil diperbarui.', 'success');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating image:', error);
                this.showToast('Gagal mengupdate gambar.', 'error');
            });
    }

    /**
     * Open the delete modal for a single image.
     */
    deleteImage(imageId) {
        const form = document.getElementById('form-delete-modal');
        if (form) form.setAttribute('data-ajax-delete', 'true');

        window.dispatchEvent(new CustomEvent('open-delete-modal', {
            detail: {
                action: `${this.baseUrl}/images/${imageId}`,
                message: 'Apakah Anda yakin ingin menghapus gambar ini?'
            }
        }));
    }

    /**
     * Open the delete modal for all images.
     */
    deleteAllImages() {
        const form = document.getElementById('form-delete-modal');
        if (form) form.setAttribute('data-ajax-delete', 'true');

        window.dispatchEvent(new CustomEvent('open-delete-modal', {
            detail: {
                action: `${this.baseUrl}/${this.subServiceId}/images`,
                message: 'PERINGATAN: Semua gambar akan dihapus permanen. Lanjutkan?'
            }
        }));
    }

    /**
     * Intercept the delete modal form submission to handle AJAX deletes
     * instead of standard form POST.
     */
    setupAjaxDeleteInterceptor() {
        const form = document.getElementById('form-delete-modal');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            if (form.getAttribute('data-ajax-delete') !== 'true') return;

            e.preventDefault();
            window.axios.post(form.getAttribute('action'), {
                _token: this.csrfToken,
                _method: 'DELETE'
            })
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error deleting:', error);
                    this.showToast('Gagal menghapus gambar.', 'error');
                });
        });
    }

    /**
     * Create a toast notification using the existing #toast-container.
     */
    showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const isSuccess = type === 'success';
        const toast = document.createElement('div');
        toast.className = `toast-alert bg-white border ${isSuccess ? 'border-hijau/20' : 'border-red-100'} shadow-[0_4px_12px_rgba(0,0,0,0.05)] rounded-md p-3.5 flex items-start gap-3 w-max min-w-[300px] max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95`;

        const iconBg = isSuccess ? 'bg-hijau/10' : 'bg-red-50';
        const iconColor = isSuccess ? 'text-hijau' : 'text-red-600';
        const iconPath = isSuccess
            ? 'M5 13l4 4L19 7'
            : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        const title = isSuccess ? 'Berhasil' : 'Gagal';

        toast.innerHTML = `
            <div class="${iconBg} p-1.5 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${iconPath}" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-[13px] font-semibold text-gray-800">${title}</h4>
                <p class="text-[12px] text-gray-500 mt-0.5 leading-snug">${message}</p>
            </div>
            <button type="button" class="toast-close text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        toast.querySelector('.toast-close').addEventListener('click', () => toast.remove());

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'scale-95');
            toast.classList.add('opacity-100', 'scale-100');
        });
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'scale-100');
            toast.classList.add('opacity-0', 'scale-95');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
}

export function initSubServiceGallery() {
    new SubServiceGalleryManager();
}
