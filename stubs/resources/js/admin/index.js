import { initAdminSidebar } from './layout/sidebar';
import { initAdminTopbar } from './layout/topbar';
import { initAdminLoading } from './layout/loading';
import { initAdminLogout } from './auth/logout';
import { initSessionSync } from './auth/session-sync';
import { initAdminLogin } from './auth/login';
import { initRateLimit } from './auth/rate-limit';
import { initIdleTimeout } from './auth/idle-timeout';
import { initAdminSubnav } from './layout/subnav';

import { initShieldPermissions } from './features/shield-permissions';
import { initToastNotification } from './features/toast-notification';
import { initDataTable } from './features/data-table';
import { initBFCacheInvalidation } from './auth/bfcache';
import { initDeleteModal } from './features/delete-modal';
import { initImagePreview } from './features/image-preview';
import { initImageCropper } from './features/image-cropper';
import { initGlobalSearch } from './features/global-search';
import { initSubServiceGallery } from './features/sub-service-gallery';
import { initLogoInstansi } from './features/logo-instansi';
import { initMultiImageUpload } from './features/multi-image-upload';
import { initDashboardStats } from './features/dashboard-stats';
import { initProfileSettings } from './features/profile-settings';
import { initFormProtector } from './features/form-protector';
import { initRecaptcha } from './features/recaptcha';
import { initAdminAnimations } from './animations/index';

import 'cropperjs/dist/cropper.css';

export function initAdminModules() {
    initFormProtector();
    initAdminAnimations();
    initAdminLoading();
    initAdminSidebar();
    initAdminTopbar();
    initAdminSubnav();
    initAdminLogout();
    initSessionSync();
    initAdminLogin();
    initRateLimit();
    initIdleTimeout();

    initShieldPermissions();
    initToastNotification();
    initDataTable();
    initBFCacheInvalidation();
    initDeleteModal();
    initImagePreview();
    initImageCropper();
    initGlobalSearch();
    initSubServiceGallery();
    initLogoInstansi();
    initMultiImageUpload();
    initDashboardStats();
    initProfileSettings();
    initRecaptcha();
}
