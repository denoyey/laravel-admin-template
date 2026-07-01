/**
 * Multi Image Upload Manager
 *
 * Manages the multi-image upload component interactions:
 * - File selection with DataTransfer accumulation
 * - Preview rendering with remove/edit buttons
 * - Crop integration via AdminCropper
 * - Clear all functionality
 *
 * Supports multiple instances per page via unique IDs.
 */

class MultiImageUploadInstance {
    constructor(container) {
        this.id = container.dataset.uploadId;
        this.hideCover = container.dataset.hideCover === 'true';
        this.fileInput = document.getElementById(this.id);
        this.replacerInput = document.getElementById(`${this.id}-single-replacer`);
        this.previewContainer = document.getElementById(`${this.id}-preview-container`);
        this.previewList = document.getElementById(`${this.id}-preview-list`);
        this.clearAllBtn = document.getElementById(`${this.id}-clear-all`);

        if (!this.fileInput) return;

        this.dataTransfer = new DataTransfer();
        this.bindEvents();
    }

    bindEvents() {
        this.fileInput.addEventListener('change', (e) => this.handleFileChange(e));
        this.fileInput.addEventListener('image-cropped', (e) => this.handleCropped(e));

        if (this.clearAllBtn) {
            this.clearAllBtn.addEventListener('click', () => {
                this.dataTransfer = new DataTransfer();
                this.fileInput.files = this.dataTransfer.files;
                this.renderPreviews();
            });
        }
    }

    handleFileChange(e) {
        const files = Array.from(e.target.files);
        if (files.length === 0 && this.dataTransfer.files.length === 0) {
            this.hidePreview();
            return;
        }

        files.forEach(file => {
            let exists = false;
            for (let i = 0; i < this.dataTransfer.files.length; i++) {
                if (this.dataTransfer.files[i].name === file.name && this.dataTransfer.files[i].size === file.size) {
                    exists = true;
                    break;
                }
            }
            if (!exists) {
                this.dataTransfer.items.add(file);
            }
        });

        this.fileInput.files = this.dataTransfer.files;
        this.renderPreviews();
    }

    handleCropped(e) {
        e.preventDefault();
        const { file, index } = e.detail;

        if (index !== null && index !== undefined) {
            const newDataTransfer = new DataTransfer();
            Array.from(this.dataTransfer.files).forEach((oldFile, i) => {
                newDataTransfer.items.add(i === index ? file : oldFile);
            });
            this.dataTransfer = newDataTransfer;
            this.fileInput.files = this.dataTransfer.files;
            this.renderPreviews();
        }
    }

    renderPreviews() {
        const currentAlts = [];
        let currentCover = null;

        if (this.previewList) {
            const inputs = this.previewList.querySelectorAll('input[name^="new_alts"]');
            inputs.forEach((input, i) => {
                currentAlts[i] = input.value;
            });

            const radios = this.previewList.querySelectorAll('input[name="new_cover"]');
            radios.forEach((radio, i) => {
                if (radio.checked) {
                    currentCover = i;
                }
            });
        }

        this.previewList.innerHTML = '';

        if (this.dataTransfer.files.length === 0) {
            this.hidePreview();
            return;
        }

        this.showPreview();

        Array.from(this.dataTransfer.files).forEach((file, index) => {
            const reader = new FileReader();

            const savedAlt = currentAlts[index] || '';
            const isChecked = (currentCover === index) || (index === 0 && currentCover === null);

            reader.onload = (e) => {
                const card = document.createElement('div');
                card.className = 'w-[200px] flex-shrink-0 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm relative flex flex-col';

                card.innerHTML = `
                    <div class="relative h-32 bg-gray-50 flex items-center justify-center overflow-hidden group">
                        <img id="preview-img-${this.id}-${index}" src="${e.target.result}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity" alt="Preview">
                        <button type="button" class="absolute top-2 left-2 p-1 sm:p-1.5 bg-white/90 hover:bg-white text-black rounded-full shadow-md transition-colors flex items-center justify-center btn-remove" data-index="${index}" title="Hapus Gambar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 sm:w-4 sm:h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button type="button" class="absolute top-2 right-2 p-1 sm:p-1.5 bg-white/90 hover:bg-white text-gray-600 rounded-full shadow-md transition-colors flex items-center justify-center btn-edit" data-index="${index}" title="Edit Gambar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 sm:w-4 sm:h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-3 bg-white flex-1 flex flex-col gap-2 border-t border-gray-100">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Alt Text (SEO)</label>
                            <input type="text" name="new_alts[${index}]" value="${savedAlt.replace(/"/g, '&quot;')}" placeholder="Opsional..." class="w-full text-[10px] border border-gray-200 rounded px-1.5 py-1 focus:outline-none focus:border-hijau focus:ring-1 focus:ring-hijau transition-colors">
                        </div>
                        ${this.hideCover ? '' : `
                        <label class="flex items-center gap-1.5 cursor-pointer mt-auto pt-1">
                            <input type="radio" name="new_cover" value="${index}" ${isChecked ? 'checked' : ''} class="w-3.5 h-3.5 text-hijau focus:ring-hijau border-gray-300">
                            <span class="text-[11px] font-medium text-gray-700">Jadikan Cover</span>
                        </label>`}
                    </div>
                `;

                card.querySelector('.btn-remove').addEventListener('click', () => {
                    this.removeFile(index);
                });

                card.querySelector('.btn-edit').addEventListener('click', () => {
                    this.editFile(index);
                });

                this.previewList.appendChild(card);
            };

            reader.readAsDataURL(file);
        });
    }

    removeFile(indexToRemove) {
        const cardToRemove = this.previewList.children[indexToRemove];
        if (cardToRemove) {
            cardToRemove.remove();
        }

        const newDataTransfer = new DataTransfer();
        Array.from(this.dataTransfer.files).forEach((file, index) => {
            if (index !== indexToRemove) {
                newDataTransfer.items.add(file);
            }
        });
        this.dataTransfer = newDataTransfer;
        this.fileInput.files = this.dataTransfer.files;
        this.renderPreviews();
    }

    editFile(indexToEdit) {
        const previewImg = document.getElementById(`preview-img-${this.id}-${indexToEdit}`);
        const file = this.dataTransfer.files[indexToEdit];
        if (window.AdminCropper && previewImg && file) {
            window.AdminCropper.originalFileName = file.name;
            window.AdminCropper.openCropper(previewImg.src, this.fileInput, previewImg, indexToEdit);
        }
    }

    showPreview() {
        this.previewContainer.classList.remove('hidden');
        this.previewContainer.classList.add('flex');
    }

    hidePreview() {
        this.previewContainer.classList.add('hidden');
        this.previewContainer.classList.remove('flex');
    }
}

export function initMultiImageUpload() {
    document.querySelectorAll('[data-upload-id]').forEach(container => {
        new MultiImageUploadInstance(container);
    });
}
