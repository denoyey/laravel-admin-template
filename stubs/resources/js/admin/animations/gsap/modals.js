import gsap from 'gsap';
import { ModalController } from '../../utils/modal-controller';

export function bindModalAnimations() {


    const originalOpen = ModalController.prototype.open;
    const originalClose = ModalController.prototype.close;

    ModalController.prototype.open = function() {
        const target = this.modal;
        if (!target) return originalOpen.call(this);

        const backdrop = target.querySelector('.absolute.inset-0');
        const content = target.querySelector('.relative.bg-white');

        gsap.killTweensOf([target, backdrop, content]);


        gsap.set(target, { autoAlpha: 1 });

        if (backdrop) {
            gsap.fromTo(backdrop,
                { autoAlpha: 0 },
                { autoAlpha: 1, duration: 0.3, ease: 'power2.out' }
            );
        }
        if (content) {
            gsap.fromTo(content,
                { autoAlpha: 0, scale: 0.85, y: 30 },
                { autoAlpha: 1, scale: 1, y: 0, duration: 0.5, ease: 'back.out(1.5)' }
            );
        }

        originalOpen.call(this);
    };

    ModalController.prototype.close = function() {
        const target = this.modal;
        if (!target) return originalClose.call(this);

        const backdrop = target.querySelector('.absolute.inset-0');
        const content = target.querySelector('.relative.bg-white');

        gsap.killTweensOf([target, backdrop, content]);

        if (backdrop) {
            gsap.to(backdrop, { autoAlpha: 0, duration: 0.2, ease: 'power2.in' });
        }
        if (content) {
            gsap.to(content, { autoAlpha: 0, scale: 0.95, y: 15, duration: 0.2, ease: 'power2.in' });
        }


        gsap.to(target, {
            autoAlpha: 0,
            duration: 0.2,
            delay: 0.1,
            onComplete: () => {
                originalClose.call(this);
            }
        });
    };
}
