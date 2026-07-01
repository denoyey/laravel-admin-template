import gsap from 'gsap';

export function initLayoutAnimation() {
    const content = document.getElementById('admin-content');

    const tlMaster = gsap.timeline();

    if (content) {
        const pageHeader = content.querySelector('.mb-3, .mb-4, .mb-6');
        const mainContainer = content.querySelector('.p-4');
        const mainContentBlocks = mainContainer ? Array.from(mainContainer.children).filter(el => !el.classList.contains('mb-3') && !el.classList.contains('mb-4') && !el.classList.contains('mb-6')) : [];
        const footer = content.querySelector('footer');

        if (pageHeader) {
            tlMaster.fromTo(pageHeader,
                { autoAlpha: 0 },
                { autoAlpha: 1, duration: 0.8, ease: 'power2.out', clearProps: 'all' },
                '-=0.3'
            );
        }

        if (mainContentBlocks.length) {
            tlMaster.fromTo(mainContentBlocks,
                { y: -15, autoAlpha: 0 },
                { y: 0, autoAlpha: 1, duration: 0.5, ease: 'power2.out', stagger: 0.1, clearProps: 'all' },
                '-=0.6'
            );
        }

        if (footer) {
            tlMaster.fromTo(footer,
                { autoAlpha: 0 },
                { autoAlpha: 1, duration: 0.5, ease: 'power2.out', clearProps: 'all' },
                '-=0.2'
            );
        }
    }
}
