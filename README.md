# Laravel Admin Template & Auth Starter Kit

Sebuah *starter kit* profesional bergaya modern untuk membangun Dashboard Admin dan sistem Autentikasi (Login/Logout) di Laravel 12. Didesain dengan prinsip OOP (*Object-Oriented Programming*) yang ketat dan *Clean Code*.

Dikembangkan oleh **denoyey**.

---

## 🌟 Fitur Utama
- **One-Command Install:** Proses instalasi otomatis layaknya Laravel Breeze.
- **Sistem Keamanan Berlapis:** Meliputi *Rate Limiting*, *Security Headers*, pencegahan *Back History*, dan *Cross-Tab Session Sync*.
- **Auto-Convert WebP:** Sudah dilengkapi komponen *upload* gambar mutakhir dengan integrasi `Cropper.js` untuk menghasilkan gambar WebP yang super ringan.
- **Tailwind CSS v4 & Vite:** Menggunakan standar arsitektur CSS terbaru dari ekosistem Laravel 12.
- **Role-Based Access Control:** Ditenagai oleh *Spatie Permission* yang siap pakai.

---

## 🚀 Panduan Instalasi (Hanya 3 Menit!)

Pastikan Anda menginstalnya pada proyek **Laravel 12** yang masih **baru dan bersih** (fresh install).

### 1. Require Package
Tarik package ini ke dalam proyek Anda melalui Composer:
```bash
composer require denoyey/laravel-admin-template
```

### 2. Jalankan Installer Otomatis
Eksekusi *command* ajaib ini. Sistem akan menyalin semua file UI, mengatur *routes*, serta menginstal semua kebutuhan Composer (Spatie & Intervention) dan NPM (Tailwind, GSAP, dll) secara gaib di belakang layar.

```bash
php artisan denoyey:install
```
*(Proses ini membutuhkan waktu beberapa saat karena sistem sedang mengunduh Node Modules dan mem-build aset frontend Anda)*.

### 3. Setup Database
Pastikan kredensial database di file `.env` Anda sudah diatur dengan benar, lalu jalankan migrasi dan seeder:
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

Selesai! 🔥 
Jalankan `php artisan serve` dan buka **`/portal-admin/login`** di browser Anda.

---

## 🛠️ Komponen Tambahan (Upload WebP)
Template ini menyertakan komponen Blade `<x-admin.forms.multi-image-upload />`. 
Anda cukup memanggil komponen ini di dalam form Anda, dan ia akan mengurus sisanya: pratinjau gambar, antarmuka *cropping* untuk user, dan pengubahan format otomatis menjadi `.webp` demi SEO dan kecepatan *loading* yang maksimal.

## 🔒 Catatan Keamanan
Secara *default*, *middleware* keamanan sudah terpasang global pada rute `/portal-admin`. Pengguna tidak akan bisa mengakses halaman dashboard tanpa melewati autentikasi, dan tidak akan bisa *back* ke dashboard setelah melakukan proses *logout*.

---
*Dibuat dengan dedikasi tinggi untuk developer Laravel.*
