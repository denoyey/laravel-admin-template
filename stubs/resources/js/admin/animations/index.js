import { initLoginAnimation } from './gsap/login';
import { initLayoutAnimation } from './gsap/layout';
import { bindModalAnimations } from './gsap/modals';

export function initAdminAnimations() {
    bindModalAnimations();
    initLoginAnimation();
    initLayoutAnimation();
}
