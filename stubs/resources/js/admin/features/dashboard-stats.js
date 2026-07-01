export function initDashboardStats() {
    const slider = document.getElementById('stat-slider');
    if (!slider) return;

    const controls = document.getElementById('stat-slider-controls');
    const btnPrev = document.getElementById('stat-prev');
    const btnNext = document.getElementById('stat-next');

    const checkOverflow = () => {
        if (!controls) return;
        if (slider.scrollWidth > slider.clientWidth + 5) {
            controls.classList.remove('hidden');
            controls.classList.add('flex');
        } else {
            controls.classList.add('hidden');
            controls.classList.remove('flex');
        }
    };

    checkOverflow();
    window.addEventListener('resize', checkOverflow);

    btnNext?.addEventListener('click', () => {
        const scrollAmount = window.innerWidth < 1280 ? slider.clientWidth * 0.8 : slider.clientWidth / 2;
        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });

    btnPrev?.addEventListener('click', () => {
        const scrollAmount = window.innerWidth < 1280 ? slider.clientWidth * 0.8 : slider.clientWidth / 2;
        slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    const counters = document.querySelectorAll('.stat-counter');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        if (target === 0) return;

        counter.innerText = '0';

        let startTimestamp = null;
        const duration = 1200;

        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);

            const currentCount = Math.floor(progress * target);
            counter.innerText = currentCount.toLocaleString('id-ID');

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = target.toLocaleString('id-ID');
            }
        };
        window.requestAnimationFrame(step);
    });
}
