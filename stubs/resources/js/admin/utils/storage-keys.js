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
    LOGOUT_TRIGGER: 'denoyey-logout-trigger',
    LOGIN_TRIGGER: 'denoyey-login-trigger',
    RATE_LIMITED: 'denoyey-rate-limited',
});

export { StorageKeys };
