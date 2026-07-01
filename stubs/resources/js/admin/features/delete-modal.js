import { DomHelper } from '../utils/dom-helper';
import { ModalController } from '../utils/modal-controller';

class DeleteModalManager {
    constructor() {
        this.messageEl = DomHelper.id('delete-modal-message');
        this.formModal = DomHelper.id('form-delete-modal');

        this.modalController = new ModalController('delete-modal', {
            backdropId: 'delete-modal-backdrop',
            cancelBtnId: 'btn-cancel-delete',
            enableEscape: true,
            onClose: () => {
                if (this.formModal) {
                    this.formModal.setAttribute('action', '');
                }
            },
        });

        this.handleFormSubmit = this.handleFormSubmit.bind(this);
    }

    init() {
        if (!this.modalController.modal) return;

        document.addEventListener('submit', (event) => {
            if (event.target && event.target.matches('.form-delete-action')) {
                this.handleFormSubmit(event);
            }
        });

        window.addEventListener('open-delete-modal', (e) => {
            const { action, message, inputs } = e.detail;

            if (this.messageEl) {
                this.messageEl.textContent = message || 'Apakah Anda yakin ingin menghapus data ini?';
            }

            if (this.formModal) {
                this.formModal.setAttribute('action', action);


                this.formModal.querySelectorAll('input.copied-input').forEach(el => el.remove());

                if (inputs) {
                    inputs.forEach(inputData => {
                        const inputEl = document.createElement('input');
                        inputEl.type = 'hidden';
                        inputEl.name = inputData.name;
                        inputEl.value = inputData.value;
                        inputEl.classList.add('copied-input');
                        this.formModal.appendChild(inputEl);
                    });
                }
            }

            this.modalController.open();
        });
    }

    handleFormSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const message = form.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
        const action = form.getAttribute('action');

        if (this.messageEl) {
            this.messageEl.textContent = message;
        }

        if (this.formModal) {
            this.formModal.setAttribute('action', action);
        }

        this.modalController.open();
    }
}

export function initDeleteModal() {
    const deleteModalManager = new DeleteModalManager();
    deleteModalManager.init();
}
