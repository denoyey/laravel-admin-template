import axios from 'axios';

export function initProfileSettings() {
    const form = document.getElementById('profile-form');
    if (!form) return;

    const loadingOverlay = document.getElementById('profile-form-loading');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'bg-red-50'));
        form.querySelectorAll('.text-red-500.text-\\[10px\\]').forEach(el => el.remove());

        if (loadingOverlay) loadingOverlay.classList.remove('hidden');

        const formData = new FormData(form);

        try {
            const response = await axios.post(form.action, formData, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.data.success) {
                if (window.showToast) {
                    window.showToast('success', 'Berhasil!', response.data.message);
                }

                if (window.updateFormTrackerState) {
                    window.updateFormTrackerState(form);
                }

                const nameDisplay = document.querySelectorAll('.user-display-name');
                const emailDisplay = document.querySelectorAll('.user-display-email');
                const initialDisplay = document.querySelectorAll('.user-display-initial');

                const initials = response.data.data.username.substring(0, 1).toUpperCase();

                nameDisplay.forEach(el => el.innerText = response.data.data.display_name);
                emailDisplay.forEach(el => el.innerText = response.data.data.email);
                initialDisplay.forEach(el => el.innerText = initials);
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                const isNoChangeError = error.response.data.message === 'Tidak ada perubahan data yang disimpan.';

                if (isNoChangeError) {
                    if (window.showToast) {
                        window.showToast('info', 'Info', error.response.data.message);
                    }
                    form.dataset.isSubmitting = 'false';
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                        if (submitBtn.dataset.originalText) submitBtn.innerText = submitBtn.dataset.originalText;
                    }
                    return;
                }

                const errors = error.response.data.errors;
                if (errors) {
                    for (const field in errors) {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('border-red-500', 'bg-red-50');
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'text-red-500 text-[10px] mt-1 font-medium';
                            errorMsg.innerText = errors[field][0];
                            input.parentNode.appendChild(errorMsg);
                        }
                    }
                }
            } else {
                console.error(error);
                if (window.showToast) {
                    window.showToast('error', 'Gagal!', 'Terjadi kesalahan sistem.');
                } else {
                    alert('Terjadi kesalahan sistem.');
                }
            }
        } finally {
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
        }
    });
}
