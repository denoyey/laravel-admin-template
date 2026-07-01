export function initFormProtector() {
    const metaCooldown = document.querySelector('meta[name="form-cooldown"]');
    const COOLDOWN_MS = metaCooldown ? parseInt(metaCooldown.content, 10) : 3000;
    const STORAGE_KEY = 'global_form_submit_lock';

    const lastSubmit = localStorage.getItem(STORAGE_KEY);
    if (lastSubmit) {
        const timePassed = Date.now() - parseInt(lastSubmit, 10);
        if (timePassed < COOLDOWN_MS) {
            const remainingTime = COOLDOWN_MS - timePassed;

            const forms = document.querySelectorAll('form:not(.no-protector)');
            forms.forEach(form => {
                form.dataset.isSubmitting = 'true';
                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn) {
                    if (!submitBtn.dataset.originalHtml) {
                        submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                    }
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    submitBtn.innerText = 'Wait..';

                    setTimeout(() => {
                        form.dataset.isSubmitting = 'false';
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                        submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                    }, remainingTime);
                } else {
                    setTimeout(() => {
                        form.dataset.isSubmitting = 'false';
                    }, remainingTime);
                }
            });
        }
    }

    const formsState = new WeakMap();

    const getFormState = (form) => {
        const state = {};
        const elements = form.querySelectorAll('input, select, textarea');
        elements.forEach(el => {
            if (el.name && el.type !== 'file' && el.name !== '_token' && el.name !== '_method') {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    const key = el.name + '_' + el.value;
                    state[key] = el.checked;
                } else if (el.tagName === 'SELECT' && el.multiple) {
                    state[el.name] = Array.from(el.selectedOptions).map(o => o.value);
                } else {
                    state[el.name] = el.value;
                }
            }
        });
        return JSON.stringify(state);
    };

    const hasFileChanges = (form) => {
        let changed = false;
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            if (input.files && input.files.length > 0) changed = true;
        });

        const hiddenFileInputs = form.querySelectorAll('input[type="hidden"][name^="cropped_"], input[type="hidden"][name^="delete_image"]');
        hiddenFileInputs.forEach(input => {
            if (input.value && input.value.trim() !== '') changed = true;
        });
        return changed;
    };

    window.updateFormTrackerState = (form) => {
        if (!form) return;
        formsState.set(form, getFormState(form));
    };

    setTimeout(() => {
        document.querySelectorAll('form:not(.no-protector):not(.no-tracker)').forEach(form => {
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput && ['PUT', 'PATCH'].includes(methodInput.value.toUpperCase())) {
                formsState.set(form, getFormState(form));
            }
        });
    }, 500);

    document.addEventListener('submit', (e) => {
        const form = e.target;

        if (form && form.tagName === 'FORM') {
            if (form.classList.contains('no-protector')) return;

            if (!form.classList.contains('no-tracker') && formsState.has(form)) {
                const initialState = formsState.get(form);
                const currentState = getFormState(form);
                const fileChanged = hasFileChanges(form);

                if (currentState === initialState && !fileChanged) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if (window.showToast) {
                        window.showToast('info', 'Tidak Ada Perubahan', 'Anda belum mengubah data apapun pada form ini.');
                    }

                    setTimeout(() => {
                        form.dataset.isSubmitting = 'false';
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                            if (submitBtn.dataset.originalHtml) {
                                submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                            }
                        }
                    }, 10);
                    return;
                }
            }

            if (form.dataset.isSubmitting === 'true') {
                e.preventDefault();
                e.stopImmediatePropagation();
                return;
            }

            form.dataset.isSubmitting = 'true';
            localStorage.setItem(STORAGE_KEY, Date.now().toString());

            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn) {
                if (!submitBtn.dataset.originalHtml) {
                    submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                }

                setTimeout(() => {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    submitBtn.innerText = 'Wait..';
                }, 0);

                setTimeout(() => {
                    form.dataset.isSubmitting = 'false';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                }, COOLDOWN_MS);
            } else {
                setTimeout(() => {
                    form.dataset.isSubmitting = 'false';
                }, COOLDOWN_MS);
            }
        }
    }, true);
}
