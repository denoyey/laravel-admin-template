import { DomHelper } from '../utils/dom-helper';

class DataTable {
    constructor() {
        this.init();
    }

    init() {
        this.initSearchHandler();
        this.initFormSubmitHandler();
        this.initPaginationHandler();
        this.initPerPageHandler();
        this.initBulkActionsHandler();
        this.initBulkDeleteFormHandler();
        this.initSelectAllState();
        this.cleanupUrlOnLoad();
        this.initLivewireHooks();
    }

    initLivewireHooks() {
        const setupHook = () => {
            if (window.Livewire) {
                window.Livewire.hook('morph.updated', () => {
                    document.querySelectorAll('.data-table-wrapper').forEach(wrapper => {
                        this.initSelectAllState();
                        this.updateBulkActionsUI(wrapper);
                    });
                });
            }
        };

        if (typeof document !== 'undefined') {
            if (window.Livewire) {
                setupHook();
            } else {
                document.addEventListener('livewire:initialized', setupHook);
            }
        }
    }

    buildTableUrl(wrapper, overrides = {}) {
        const params = new URLSearchParams(window.location.search);

        const searchInput = DomHelper.select('input[name="search"]', wrapper);
        if (searchInput) params.set('search', searchInput.value);

        const perPageSelect = DomHelper.select('select[name="per_page"]', wrapper);
        if (perPageSelect) params.set('per_page', perPageSelect.value);

        for (const [key, value] of Object.entries(overrides)) {
            params.set(key, value);
        }

        if (!overrides.page) params.delete('page');

        const keysToDelete = [];
        for (const [key, value] of params.entries()) {
            if (!value || value.trim() === '' || (key === 'per_page' && value === '10') || (key === 'page' && value === '1')) {
                keysToDelete.push(key);
            }
        }
        keysToDelete.forEach(k => params.delete(k));

        const queryString = params.toString();
        return window.location.pathname + (queryString ? '?' + queryString : '');
    }

    fetchTableData(url, wrapper) {
        const tbody = DomHelper.select('.data-table-body', wrapper);
        if (tbody) tbody.style.opacity = '0.5';

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newWrapper = DomHelper.select('.data-table-wrapper', doc);
                if (newWrapper) {
                    const newTbody = DomHelper.select('.data-table-body', newWrapper);
                    if (newTbody && tbody) tbody.innerHTML = newTbody.innerHTML;

                    const newPagination = DomHelper.select('.data-table-pagination', newWrapper);
                    const pagination = DomHelper.select('.data-table-pagination', wrapper);
                    if (newPagination && pagination) {
                        pagination.innerHTML = newPagination.innerHTML;
                    } else if (newPagination && !pagination) {
                        wrapper.appendChild(newPagination);
                    } else if (!newPagination && pagination) {
                        pagination.remove();
                    }
                }

                this.initSelectAllState();


                document.querySelectorAll('.data-table-wrapper').forEach(w => this.updateBulkActionsUI(w));

                if (tbody) tbody.style.opacity = '1';
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                if (tbody) tbody.style.opacity = '1';
            });
    }

    initSearchHandler() {
        document.addEventListener('input', (e) => {
            if (e.target.name === 'search' && e.target.closest('.data-table-wrapper')) {
                const wrapper = e.target.closest('.data-table-wrapper');
                clearTimeout(wrapper.dataTableSearchTimeout);
                wrapper.dataTableSearchTimeout = setTimeout(() => {
                    this.fetchTableData(this.buildTableUrl(wrapper), wrapper);
                }, 500);
            }
        });
    }

    initFormSubmitHandler() {
        document.addEventListener('submit', (e) => {
            if (e.target.closest('.data-table-wrapper') && DomHelper.select('input[name="search"]', e.target)) {
                e.preventDefault();
            }
        });
    }

    initPaginationHandler() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('.data-table-pagination a');
            if (link) {
                e.preventDefault();
                const wrapper = link.closest('.data-table-wrapper');
                const urlObj = new URL(link.href);
                const overrides = {};
                if (urlObj.searchParams.has('page')) overrides.page = urlObj.searchParams.get('page');
                this.fetchTableData(this.buildTableUrl(wrapper, overrides), wrapper);
            }
        });
    }

    initPerPageHandler() {
        document.addEventListener('change', (e) => {
            if (e.target.name === 'per_page' && e.target.closest('.data-table-pagination')) {
                e.preventDefault();
                const wrapper = e.target.closest('.data-table-wrapper');
                this.fetchTableData(this.buildTableUrl(wrapper), wrapper);
            }
        });
    }

    initBulkActionsHandler() {
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('select-all') || e.target.classList.contains('row-checkbox')) {
                const wrapper = e.target.closest('.data-table-wrapper');
                if (!wrapper) return;

                const selectAllCb = wrapper.querySelector('.select-all');
                const rowCbs = Array.from(wrapper.querySelectorAll('.row-checkbox:not(:disabled)'));

                if (e.target.classList.contains('select-all')) {
                    const isChecked = e.target.checked;
                    rowCbs.forEach(cb => cb.checked = isChecked);
                } else if (e.target.classList.contains('row-checkbox')) {
                    if (selectAllCb) {
                        const allChecked = rowCbs.length > 0 && rowCbs.every(cb => cb.checked);
                        const someChecked = rowCbs.some(cb => cb.checked);
                        selectAllCb.checked = allChecked;
                        selectAllCb.indeterminate = someChecked && !allChecked;
                    }
                }

                this.updateBulkActionsUI(wrapper);
            }
        });
    }

    updateBulkActionsUI(wrapper) {
        const rowCbs = Array.from(wrapper.querySelectorAll('.row-checkbox:not(:disabled)'));
        const checkedCount = rowCbs.filter(cb => cb.checked).length;

        const bulkContainer = wrapper.querySelector('.bulk-actions-container');
        const countText = wrapper.querySelector('.selected-count-text');

        if (bulkContainer) {
            if (checkedCount > 0) {
                if (countText) countText.textContent = checkedCount;
                bulkContainer.classList.remove('hidden');
                bulkContainer.classList.add('flex');
            } else {
                bulkContainer.classList.add('hidden');
                bulkContainer.classList.remove('flex');
            }
        }
    }

    initBulkDeleteFormHandler() {
        document.addEventListener('submit', (e) => {
            if (e.target.id === 'bulk-delete-form') {
                e.preventDefault();
                const wrapper = e.target.closest('.data-table-wrapper');
                if (!wrapper) return;

                const selectedIds = Array.from(wrapper.querySelectorAll('.row-checkbox:not(:disabled):checked'))
                                         .map(cb => cb.value);

                if (selectedIds.length === 0) return;

                const message = 'Anda yakin ingin menghapus ' + selectedIds.length + ' item yang dipilih secara permanen?';
                const action = e.target.getAttribute('action');

                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    detail: {
                        action: action,
                        message: message,
                        inputs: [
                            { name: 'ids', value: JSON.stringify(selectedIds) }
                        ]
                    }
                }));
            }
        });
    }

    initSelectAllState() {
        document.querySelectorAll('.data-table-wrapper').forEach(wrapper => {
            const selectAllCb = wrapper.querySelector('.select-all');
            if (!selectAllCb) return;

            const rowCbs = wrapper.querySelectorAll('.row-checkbox:not(:disabled)');
            if (rowCbs.length === 0) {
                selectAllCb.disabled = true;
                selectAllCb.checked = false;
                selectAllCb.classList.add('cursor-not-allowed', 'opacity-50');
                selectAllCb.classList.remove('cursor-pointer');
            } else {
                selectAllCb.disabled = false;
                selectAllCb.classList.remove('cursor-not-allowed', 'opacity-50');
                selectAllCb.classList.add('cursor-pointer');
            }
        });
    }

    cleanupUrlOnLoad() {
        const params = new URLSearchParams(window.location.search);
        let urlChanged = false;
        const keysToDelete = [];

        for (const [key, value] of params.entries()) {
            if (!value || value.trim() === '' || (key === 'per_page' && value === '10') || (key === 'page' && value === '1')) {
                keysToDelete.push(key);
                urlChanged = true;
            }
        }

        if (urlChanged) {
            keysToDelete.forEach(k => params.delete(k));
            const queryString = params.toString();
            const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
            window.history.replaceState({}, '', newUrl);
        }
    }
}

export function initDataTable() {
    new DataTable();
}
