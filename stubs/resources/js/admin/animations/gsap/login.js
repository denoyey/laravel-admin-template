import gsap from 'gsap';

export function initLoginAnimation() {
    const loginForm = document.querySelector('form[action*="login"]');
    if (!loginForm) return;

    const loginCard = loginForm.closest('.max-w-sm');
    if (!loginCard) return;

    const logo = loginCard.querySelector('img');
    const title = loginCard.querySelector('h2');
    const formElements = loginCard.querySelectorAll('form > div');
    const errorAlert = loginCard.querySelector('.bg-red-50');

    if (errorAlert) return;

    const tlMaster = gsap.timeline();

    tlMaster.fromTo(loginCard,
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }
    );

    if (logo) {
        tlMaster.fromTo(logo,
            { opacity: 0, scale: 0.5 },
            { opacity: 1, scale: 1, duration: 0.6, ease: 'back.out(1.5)' },
            '-=0.4' // Start a bit early before the card finishes
        );
    }

    if (title) {
        tlMaster.fromTo(title,
            { opacity: 0, y: 10 },
            { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' },
            '-=0.3'
        );
    }

    if (formElements.length) {
        tlMaster.fromTo(formElements,
            { opacity: 0, y: 15 },
            { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out', stagger: 0.1 },
            '-=0.2'
        );
    }
}
