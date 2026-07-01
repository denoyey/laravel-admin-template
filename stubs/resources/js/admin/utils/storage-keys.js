/**
 * StorageKeys - Centralized constant for all localStorage keys used in the application.
 *
 * Eliminates magic strings scattered across multiple files and
 * makes all storage keys discoverable via IDE autocomplete.
 *
 * Usage:
 *   import { StorageKeys } from '../utils/storage-keys';
 *   localStorage.setItem(StorageKeys.LOGOUT_TRIGGER, Date.now().toString());
 */
const StorageKeys = Object.freeze({
    LOGOUT_TRIGGER: 'ksa-logout-trigger',
    LOGIN_TRIGGER: 'ksa-login-trigger',
    RATE_LIMITED: 'ksa-rate-limited',
});

export { StorageKeys };
