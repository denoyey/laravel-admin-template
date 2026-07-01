import { PageReloader } from '../utils/page-reloader';

class BFCacheManager {
    constructor() {
        this.handlePageShow = this.handlePageShow.bind(this);
    }

    init() {
        window.addEventListener('pageshow', this.handlePageShow);
    }

    handlePageShow(event) {
        if (event.persisted) {
            PageReloader.reload();
        }
    }
}

export function initBFCacheInvalidation() {
    const bfCacheManager = new BFCacheManager();
    bfCacheManager.init();
}
