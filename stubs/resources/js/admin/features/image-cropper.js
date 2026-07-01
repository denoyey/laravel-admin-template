import Cropper from 'cropperjs';
import { ModalController } from '../utils/modal-controller';

class ImageCropperManager {
    constructor() {
        this.modalController = new ModalController('cropper-modal', {
            backdropId: 'cropper-modal-backdrop',
            cancelBtnId: 'btn-cancel-cropper',
            onClose: () => this.destroyCropper()
        });

        this.imageElement = document.getElementById('cropper-image');
        this.saveBtn = document.getElementById('btn-save-cropper');
        this.cropper = null;
        this.currentInput = null;
        this.currentPreviewImg = null;
        this.currentIndex = null;
        this.originalFileName = 'cropped_image.jpg';

        this.bindEvents();
    }

    bindEvents() {

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-crop-image');
            if (!btn) return;

            e.preventDefault();
            const wrapper = btn.closest('.image-preview-wrapper');
            if (!wrapper) return;

            const targetId = wrapper.getAttribute('data-target');
            const inputEl = wrapper.querySelector('input[type="file"]');
            const previewContainer = document.getElementById(targetId);

            if (!inputEl || !previewContainer) return;

            const previewImg = previewContainer.querySelector('.preview-img');
            if (!previewImg || !previewImg.src) return;

            if (inputEl.files && inputEl.files.length > 0) {
                this.originalFileName = inputEl.files[0].name;
            } else {
                this.originalFileName = 'cropped_image.jpg';
            }

            this.openCropper(previewImg.src, inputEl, previewImg);
        });

        document.addEventListener('click', (e) => {
            const toolBtn = e.target.closest('.cropper-tool-btn');
            if (!toolBtn || !this.cropper) return;

            const action = toolBtn.getAttribute('data-action');
            switch(action) {
                case 'zoom-in':
                    this.cropper.zoom(0.1);
                    break;
                case 'zoom-out':
                    this.cropper.zoom(-0.1);
                    break;
                case 'rotate-left':
                    this.cropper.rotate(-45);
                    break;
                case 'rotate-right':
                    this.cropper.rotate(45);
                    break;
                case 'flip-horizontal':
                    this.cropper.scaleX(this.cropper.getData().scaleX === -1 ? 1 : -1);
                    break;
                case 'flip-vertical':
                    this.cropper.scaleY(this.cropper.getData().scaleY === -1 ? 1 : -1);
                    break;
            }
        });

        if (this.saveBtn) {
            this.saveBtn.addEventListener('click', () => this.applyCrop());
        }
    }

    openCropper(imageSrc, inputElement, previewImageElement, index = null) {
        this.currentInput = inputElement;
        this.currentPreviewImg = previewImageElement;
        this.currentIndex = index;
        this.imageElement.src = imageSrc;

        this.modalController.open();

        setTimeout(() => {
            this.cropper = new Cropper(this.imageElement, {
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }, 300);
    }

    destroyCropper() {
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
        this.imageElement.src = '';
        this.currentInput = null;
        this.currentPreviewImg = null;
        this.currentIndex = null;
    }

    applyCrop() {
        if (!this.cropper || !this.currentInput || !this.currentPreviewImg) return;

        const canvas = this.cropper.getCroppedCanvas({
            maxWidth: 4096,
            maxHeight: 4096,
        });

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        this.currentPreviewImg.src = dataUrl;

        canvas.toBlob((blob) => {
            if (!blob) return;


            const file = new File([blob], this.originalFileName, { type: 'image/jpeg', lastModified: new Date().getTime() });


            const customCropEvent = new CustomEvent('image-cropped', {
                bubbles: true,
                cancelable: true,
                detail: {
                    file: file,
                    dataUrl: dataUrl,
                    index: this.currentIndex,
                    previewImg: this.currentPreviewImg
                }
            });

            let defaultPrevented = false;
            if (this.currentInput) {
                this.currentInput.dispatchEvent(customCropEvent);
                defaultPrevented = customCropEvent.defaultPrevented;
            }

            if (!defaultPrevented && this.currentInput) {

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                this.currentInput.files = dataTransfer.files;
            }

            this.modalController.close();
        }, 'image/jpeg', 0.9);
    }
}

export function initImageCropper() {
    window.AdminCropper = new ImageCropperManager();
}
