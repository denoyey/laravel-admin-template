export function initImagePreview() {
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[type="file"]')) {
            const wrapper = e.target.closest('.image-preview-wrapper');
            if (!wrapper) return;

            const targetId = wrapper.getAttribute('data-target');
            if (!targetId) return;

            const previewContainer = document.getElementById(targetId);
            if (!previewContainer) return;

            const img = previewContainer.querySelector('.preview-img');
            const placeholder = previewContainer.querySelector('.preview-placeholder');
            const editOverlays = previewContainer.querySelectorAll('.edit-overlay');

            const file = e.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    img.src = event.target.result;
                    img.classList.remove('hidden');
                    img.classList.add('block');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                        placeholder.classList.remove('flex');
                    }
                    if (editOverlays.length) {
                        editOverlays.forEach(overlay => {
                            overlay.classList.remove('hidden');
                            overlay.classList.add('flex');
                        });
                    }
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('block');
                };
                reader.readAsDataURL(file);
            } else {

                const originalSrc = img.getAttribute('data-original-src');
                if (originalSrc) {
                    img.src = originalSrc;
                    img.classList.remove('hidden');
                    img.classList.add('block');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                        placeholder.classList.remove('flex');
                    }
                    if (editOverlays.length) {
                        editOverlays.forEach(overlay => {
                            overlay.classList.remove('hidden');
                            overlay.classList.add('flex');
                        });
                    }
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('block');
                } else {
                    img.src = '';
                    img.classList.add('hidden');
                    img.classList.remove('block');
                    if (placeholder) {
                        placeholder.classList.remove('hidden');
                        placeholder.classList.add('flex');
                    }
                    if (editOverlays.length) {
                        editOverlays.forEach(overlay => {
                            overlay.classList.add('hidden');
                            overlay.classList.remove('flex');
                        });
                    }
                    previewContainer.classList.add('hidden');
                    previewContainer.classList.remove('block');
                }
            }
        }
    });

    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.btn-remove-image');
        if (removeBtn) {
            e.preventDefault();
            const wrapper = removeBtn.closest('.image-preview-wrapper');
            if (!wrapper) return;

            const input = wrapper.querySelector('input[type="file"]');
            if (input) {
                input.value = ''; // Clear file input

                const targetId = wrapper.getAttribute('data-target');
                if (targetId) {
                    const previewContainer = document.getElementById(targetId);
                    if (previewContainer) {
                        const img = previewContainer.querySelector('.preview-img');
                        if (img) {

                            img.setAttribute('data-original-src', '');
                        }
                    }
                }

                let removeFlag = wrapper.querySelector('input.remove-image-flag');
                if (!removeFlag) {
                    removeFlag = document.createElement('input');
                    removeFlag.type = 'hidden';
                    removeFlag.name = 'remove_' + input.name;
                    removeFlag.className = 'remove-image-flag';
                    wrapper.appendChild(removeFlag);
                }
                removeFlag.value = '1';

                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        }
    });
}
