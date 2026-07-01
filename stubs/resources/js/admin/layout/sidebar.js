import { DomHelper } from '../utils/dom-helper';

class AdminSidebar {
    constructor() {
        this.overlay = DomHelper.id('admin-overlay');
        this.toggleBtn = DomHelper.id('sidebar-toggle');
        this.mobileBtn = DomHelper.id('sidebar-mobile-open');
        this.sections = DomHelper.selectAll('.sidebar-section');
    }

    init() {
        this.initDesktopToggle();
        this.initMobileToggle();
        this.initAccordion();
    }

    initDesktopToggle() {
        if (!this.toggleBtn) return;

        this.toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }

    initMobileToggle() {
        this.mobileBtn?.addEventListener('click', () => this.openMobile());
        this.overlay?.addEventListener('click', () => this.closeMobile());

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeMobile();
        });
    }

    openMobile() {
        document.body.classList.add('sidebar-open');
        this.overlay?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    closeMobile() {
        document.body.classList.remove('sidebar-open');
        this.overlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    initAccordion() {
        this.sections.forEach(section => {
            const toggle = DomHelper.select('.section-toggle', section);
            const content = DomHelper.select('.section-content', section);
            const chevron = DomHelper.select('.chevron-icon', section);

            if (!toggle || !content) return;

            content.style.maxHeight = content.scrollHeight + 'px';

            toggle.addEventListener('click', () => {

                if (document.body.classList.contains('sidebar-collapsed') && window.innerWidth >= 1024) {
                    return;
                }

                const isClosed = section.classList.contains('is-closed');

                if (isClosed) {
                    section.classList.remove('is-closed');
                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.style.opacity = '1';
                    content.style.visibility = 'visible';
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                } else {
                    section.classList.add('is-closed');
                    content.style.maxHeight = '0px';
                    content.style.opacity = '0';

                    setTimeout(() => {
                        if (section.classList.contains('is-closed')) {
                            content.style.visibility = 'hidden';
                        }
                    }, 300);
                    if (chevron) chevron.style.transform = 'rotate(-90deg)';
                }
            });

            window.addEventListener('resize', () => {
                if (!section.classList.contains('is-closed') && (!document.body.classList.contains('sidebar-collapsed') || window.innerWidth < 1024)) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });
    }
}

export function initAdminSidebar() {
    const sidebar = new AdminSidebar();
    sidebar.init();
}
