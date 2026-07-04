<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileUploadExampleController;
use App\Http\Controllers\Admin\MultiUploadExampleController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Admin Portal Routes
Route::prefix('/portal-admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Guest routes
    Route::middleware(['guest', 'prevent-back-history'])->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });

    // Authenticated routes
    Route::middleware(['auth', 'permission:access_admin_panel', 'prevent-back-history', 'prevent-spam'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

        // Profile Settings
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
        Route::delete('roles/bulk', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
        Route::resource('roles', RoleController::class);

        Route::delete('users/bulk', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
        Route::resource('users', UserController::class);

        Route::delete('activity-logs/bulk', [ActivityLogController::class, 'bulkDelete'])->name('activity-logs.bulk-delete');
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show', 'destroy']);

        // File Upload Demo
        Route::delete('file-upload-examples/bulk', [FileUploadExampleController::class, 'bulkDelete'])->name('file-upload-examples.bulk-delete');
        Route::delete('file-upload-examples/delete-all-images/{id_file_upload}', [FileUploadExampleController::class, 'destroyAllImages'])->name('file-upload-examples.destroy-all-images');
        Route::post('file-upload-examples/update-image/{id_image}', [FileUploadExampleController::class, 'updateImage'])->name('file-upload-examples.update-image');
        Route::delete('file-upload-examples/delete-image/{id_image}', [FileUploadExampleController::class, 'destroyImage'])->name('file-upload-examples.destroy-image');
        Route::resource('file-upload-examples', FileUploadExampleController::class);

        // Multi Image Gallery
        Route::delete('multi-upload-examples/bulk', [MultiUploadExampleController::class, 'destroyAll'])->name('multi-upload-examples.destroyAll');
        Route::resource('multi-upload-examples', MultiUploadExampleController::class)->only(['index', 'store', 'update', 'destroy']);

        // Add your other admin routes here...
    });

    // Auth only
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('/keep-alive', [LoginController::class, 'keepAlive'])->name('keep-alive');
    });
});

Route::fallback(function () {
    if (request()->is('portal-admin') || request()->is('portal-admin/*')) {
        return response()->view('errors.404-admin', [], 404);
    }

    abort(404);
});
