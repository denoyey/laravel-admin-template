import { DomHelper } from '../utils/dom-helper';

class ShieldPermissions {
    constructor() {
        this.cards = DomHelper.selectAll('.permission-card');
        this.tabs = DomHelper.selectAll('.tab-btn');
        this.sections = DomHelper.selectAll('.permission-section');
        this.init();
        this.initTabs();
    }

    initTabs() {
        if (!this.tabs.length) return;

        this.tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                const targetId = tab.getAttribute('data-target');

                this.tabs.forEach(t => {
                    t.classList.remove('bg-hijau/10', 'text-hijau', 'border-hijau/20', 'active');
                    t.classList.add('bg-white', 'text-gray-600', 'border-transparent');

                    const badge = DomHelper.select('.tab-badge', t);
                    if (badge) {
                        badge.classList.remove('bg-white', 'border-hijau/20', 'shadow-sm');
                        badge.classList.add('bg-hijau/10');
                    }
                });

                tab.classList.remove('bg-white', 'text-gray-600', 'border-transparent');
                tab.classList.add('bg-hijau/10', 'text-hijau', 'border-hijau/20', 'active');

                const activeBadge = DomHelper.select('.tab-badge', tab);
                if (activeBadge) {
                    activeBadge.classList.remove('bg-hijau/10');
                    activeBadge.classList.add('bg-white', 'border-hijau/20', 'shadow-sm');
                }

                this.sections.forEach(section => {
                    section.classList.add('hidden');
                });
                const targetSection = DomHelper.id(`section-${targetId}`);
                if (targetSection) {
                    targetSection.classList.remove('hidden');
                }
            });
        });
    }

    init() {
        const globalSelectAll = DomHelper.id('global-select-all');
        const allPermissionCheckboxes = DomHelper.selectAll('input[name="permissions[]"]');

        if (globalSelectAll && allPermissionCheckboxes.length) {
            this.updateGlobalSelectAllState(globalSelectAll, allPermissionCheckboxes);

            globalSelectAll.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                allPermissionCheckboxes.forEach(cb => {
                    cb.checked = isChecked;
                });

                if (this.cards.length) {
                    this.cards.forEach(card => {
                        const selectAllCheckbox = DomHelper.select('.select-all-checkbox', card);
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = isChecked;
                            selectAllCheckbox.indeterminate = false;
                        }
                    });
                }
            });

            allPermissionCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    this.updateGlobalSelectAllState(globalSelectAll, allPermissionCheckboxes);
                });
            });
        }

        if (!this.cards.length) return;

        this.cards.forEach(card => {
            const selectAllCheckbox = DomHelper.select('.select-all-checkbox', card);
            const permissionCheckboxes = DomHelper.selectAll('.permission-checkbox', card);

            if (!selectAllCheckbox || !permissionCheckboxes.length) return;

            this.updateSelectAllState(selectAllCheckbox, permissionCheckboxes);

            selectAllCheckbox.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                permissionCheckboxes.forEach(cb => {
                    cb.checked = isChecked;
                });
            });

            permissionCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    this.updateSelectAllState(selectAllCheckbox, permissionCheckboxes);
                });
            });
        });
    }

    updateSelectAllState(selectAllCheckbox, permissionCheckboxes) {
        const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }

    updateGlobalSelectAllState(globalSelectAllCheckbox, allCheckboxes) {
        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(allCheckboxes).some(cb => cb.checked);

        globalSelectAllCheckbox.checked = allChecked;
    }
}

export function initShieldPermissions() {
    new ShieldPermissions();
}
