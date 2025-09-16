<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| Routes accessible without authentication
|
*/
Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Health Check
        |------------------------------------------------------------------
        */
        Route::get('/health', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'API is healthy',
                'timestamp' => now()->toISOString(),
                'version' => '1.0.0',
            ]);
        })->name('health');

        /*
        |------------------------------------------------------------------
        | Authentication Routes
        |------------------------------------------------------------------
        */
        Route::prefix('auth')
            ->name('auth.')
            ->controller(AuthController::class)
            ->middleware('throttle:10,1') // Global rate limiting for auth routes
            ->group(function () {

                // Login & Registration
                Route::post('login', 'login')
                    ->middleware('throttle:5,1')
                    ->name('login');

                Route::post('register', 'register')
                    ->middleware('throttle:5,1')
                    ->name('register');

                // OTP Authentication
                Route::prefix('otp')
                    ->name('otp.')
                    ->group(function () {
                        Route::post('send', 'sendOtp')
                            ->middleware('throttle:5,1')
                            ->name('send');

                        Route::post('verify', 'verifyOtp')
                            ->middleware('throttle:5,1')
                            ->name('verify');
                    });

                // Social Authentication
                Route::prefix('social')
                    ->name('social.')
                    ->group(function () {
                        Route::get('{provider}', 'socialRedirect')
                            ->where('provider', 'google|github|facebook|apple')
                            ->name('redirect');

                        Route::get('{provider}/callback', 'socialCallback')
                            ->where('provider', 'google|github|facebook|apple')
                            ->name('callback');
                    });

                // Password Reset
                Route::prefix('password')
                    ->name('password.')
                    ->group(function () {
                        Route::post('forgot', 'forgotPassword')
                            ->middleware('throttle:5,1')
                            ->name('forgot');

                        Route::post('reset', 'resetPassword')
                            ->middleware('throttle:5,1')
                            ->name('reset');
                    });
            });
    });

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to authenticated users
|
*/
Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | User Profile Routes
        |------------------------------------------------------------------
        */
        Route::prefix('profile')
            ->name('profile.')
            ->controller(ProfileController::class)
            ->group(function () {

                Route::get('/', 'show')
                    ->name('show');

                Route::put('/', 'update')
                    ->name('update');

                Route::put('password', 'updatePassword')
                    ->middleware('throttle:6,1')
                    ->name('password.update');

                Route::delete('/', 'destroy')
                    ->name('destroy');
            });

        /*
        |------------------------------------------------------------------
        | User Management Routes
        |------------------------------------------------------------------
        */
        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('{user}', 'show')
                    ->name('show');

                Route::put('{user}', 'update')
                    ->name('update');

                Route::delete('{user}', 'destroy')
                    ->name('destroy');
            });

        /*
        |------------------------------------------------------------------
        | Authentication Routes (Authenticated)
        |------------------------------------------------------------------
        */
        Route::prefix('auth')
            ->name('auth.')
            ->controller(AuthController::class)
            ->group(function () {
                Route::post('logout', 'logout')
                    ->name('logout');
            });
    });

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to admin users
|
*/
Route::prefix('v1/admin')
    ->name('api.v1.admin.')
    ->middleware(['auth:sanctum', 'admin'])
    ->controller(AdminController::class)
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Admin Dashboard
        |------------------------------------------------------------------
        */
        Route::get('dashboard', 'dashboard')
            ->name('dashboard');

        /*
        |------------------------------------------------------------------
        | Admin User Management
        |------------------------------------------------------------------
        */
        Route::prefix('users')
            ->name('users.')
            ->group(function () {

                Route::get('/', 'users')
                    ->name('index');

                Route::post('/', 'createUser')
                    ->name('store');

                Route::get('{user}', 'showUser')
                    ->name('show');

                Route::put('{user}', 'updateUser')
                    ->name('update');

                Route::delete('{user}', 'deleteUser')
                    ->name('destroy');

                Route::post('{user}/toggle-status', 'toggleUserStatus')
                    ->name('toggle-status');
            });

        /*
        |------------------------------------------------------------------
        | Admin Statistics
        |------------------------------------------------------------------
        */
        Route::prefix('stats')
            ->name('stats.')
            ->group(function () {

                Route::get('overview', 'overviewStats')
                    ->name('overview');

                Route::get('users', 'userStats')
                    ->name('users');

                Route::get('activity', 'activityStats')
                    ->name('activity');
            });
    });

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
|
| Handle undefined API routes
|
*/
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found',
        'timestamp' => now()->toISOString(),
    ], 404);
});
