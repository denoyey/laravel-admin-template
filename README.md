# Laravel Admin Template & Auth Starter Kit

A clean, modern, and OOP-based Admin Dashboard and Auth starter kit for Laravel 12.
Created by **denoyey**.

This package provides a beautifully crafted Admin Dashboard template with a fully functional Authentication system (Login/Logout), including advanced security features (Rate Limiting, Security Headers, Cross-Tab Session Sync), and auto-convert WebP image uploads using Cropper.js.

## Requirements
- PHP ^8.2
- Laravel ^12.0
- Tailwind CSS v4

## Installation Guide

Follow these steps carefully to install the template into a fresh Laravel project.

### Step 1: Install the Package
Require the package using Composer:
```bash
composer require denoyey/laravel-admin-template
```

### Step 2: Run the Install Command
Publish all the template files (Controllers, Middleware, Views, JS, CSS, Routes, etc) to your project:
```bash
php artisan denoyey:install
```
*This command will copy all necessary files, configure Tailwind v4 in `resources/css/app.css`, and add required NPM dependencies to your `package.json`.*

### Step 3: Install Required PHP Packages
This template relies on Spatie Permission for Role-Based Access Control (RBAC) and Intervention Image for WebP auto-conversion. Install them:
```bash
composer require spatie/laravel-permission intervention/image
```

### Step 4: Configure Spatie Permission
Publish the Spatie Permission configuration and migration files:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### Step 5: Setup Database & Run Migrations
Ensure your `.env` file is properly configured with your database credentials. Then run migrations and seed the default Super Admin user:
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```
*(Check `database/seeders/UserSeeder.php` to see the default login credentials).*

### Step 6: Install NPM Dependencies & Build Assets
Install the frontend libraries (Tailwind v4, GSAP, Swiper, Cropper.js, Axios) and build them:
```bash
npm install
npm run build
```

### Step 7: Configure Vite (If necessary)
Ensure your `vite.config.js` includes the admin entry points:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin-*.css', // If applicable
                'resources/js/app.js',
                'resources/js/admin.js' // Important for the admin template
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

### Step 8: Done!
Start your local server:
```bash
php artisan serve
```
Visit `http://localhost:8000/portal-admin/login` (or the route prefix defined in your routes) to access your brand new, highly secure Admin Dashboard!

## Security Features Included
- **Rate Limiting:** Protects the login route from brute-force attacks.
- **Security Headers Middleware:** Adds `X-Frame-Options`, `X-XSS-Protection`, and more to all responses.
- **Prevent Back History:** Ensures users cannot use the browser's back button to view the dashboard after logging out.
- **Cross-Tab Session Sync:** Instantly logs out the user on all open tabs if they log out in one tab.

## Uploading Images (Auto-Convert WebP)
The included `<x-admin.forms.multi-image-upload />` component handles image selection and uses `Cropper.js` to let users crop images. The backend (or frontend JS) automatically handles converting these images to `.webp` format for ultimate performance.

Enjoy building your next great application!
