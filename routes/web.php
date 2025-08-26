<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    
    // Admin profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Profile');
        })->name('edit');
        
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
    });
});

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Profile');
        })->name('edit');
        
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
    
    // Settings routes (for backward compatibility with tests)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', function () {
            return Inertia::render('Profile');
        })->name('profile');
        
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        
        Route::get('/password', function () {
            return Inertia::render('Profile');
        })->name('password');
        
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        
        // Account deletion
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
