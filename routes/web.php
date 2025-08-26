<?php

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->group(function () {
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
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return Inertia::render('Admin/Users');
    })->name('users.index');

    // Admin profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Profile');
        })->name('edit');

        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [AdminProfileController::class, 'destroy'])->name('destroy');
    });
});

// Settings routes (for backward compatibility with tests)
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('profile');

    Route::get('/password', function () {
        return Inertia::render('Profile');
    })->name('password');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Error Handling Routes
Route::get('/403', [ErrorController::class, 'forbidden'])->name('errors.403');
Route::get('/404', [ErrorController::class, 'notFound'])->name('errors.404');
Route::get('/500', [ErrorController::class, 'internalServerError'])->name('errors.500');
Route::get('/401', [ErrorController::class, 'unauthorized'])->name('errors.401');
Route::get('/429', [ErrorController::class, 'tooManyRequests'])->name('errors.429');
Route::get('/503', [ErrorController::class, 'maintenance'])->name('errors.503');
Route::get('/maintenance', [ErrorController::class, 'maintenance'])->name('errors.maintenance');
Route::get('/too-many-requests', [ErrorController::class, 'tooManyRequests'])->name('errors.429');
Route::get('/unauthorized', [ErrorController::class, 'unauthorized'])->name('errors.401');
Route::get('/bad-request', [ErrorController::class, 'badRequest'])->name('errors.400');
Route::get('/method-not-allowed', [ErrorController::class, 'methodNotAllowed'])->name('errors.405');
Route::get('/unprocessable-entity', [ErrorController::class, 'unprocessableEntity'])->name('errors.422');

// Generic error route for other status codes
Route::get('/error/{status}', [ErrorController::class, 'generic'])->name('errors.generic');

require __DIR__.'/auth.php';
