<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\OtpLogin;
use App\Http\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here are the authentication routes for the application. These routes
| are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| Guest Routes (Unauthenticated Users)
|--------------------------------------------------------------------------
|
| Routes accessible only to unauthenticated users
|
*/
Route::middleware('guest')
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Registration Routes
        |------------------------------------------------------------------
        */
        Route::get('register', Register::class)
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store'])
            ->middleware(['throttle:3,1', 'honeypot'])
            ->name('register.store');

        /*
        |------------------------------------------------------------------
        | Login Routes
        |------------------------------------------------------------------
        */
        Route::get('login', Login::class)
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->middleware(['throttle:5,1', 'honeypot'])
            ->name('login.store');

        /*
        |------------------------------------------------------------------
        | OTP Login Routes
        |------------------------------------------------------------------
        */
        Route::get('login/otp', OtpLogin::class)
            ->name('login.otp');

        /*
        |------------------------------------------------------------------
        | Social Login Routes
        |------------------------------------------------------------------
        */
        Route::prefix('login')
            ->middleware('throttle:10,1')
            ->group(function () {
                Route::get('{provider}', [SocialLoginController::class, 'redirectToProvider'])
                    ->name('social.login')
                    ->where('provider', 'google|github|facebook|apple');

                Route::get('{provider}/callback', [SocialLoginController::class, 'handleProviderCallback'])
                    ->name('social.callback')
                    ->where('provider', 'google|github|facebook|apple');
            });

        /*
        |------------------------------------------------------------------
        | Password Reset Routes
        |------------------------------------------------------------------
        */
        Route::prefix('forgot-password')
            ->name('password.')
            ->group(function () {
                Route::get('/', [PasswordResetLinkController::class, 'create'])
                    ->name('request');

                Route::post('/', [PasswordResetLinkController::class, 'store'])
                    ->middleware(['throttle:3,1', 'honeypot'])
                    ->name('email');
            });

        Route::prefix('reset-password')
            ->name('password.')
            ->group(function () {
                Route::get('{token}', [NewPasswordController::class, 'create'])
                    ->name('reset');

                Route::post('/', [NewPasswordController::class, 'store'])
                    ->middleware(['throttle:3,1', 'honeypot'])
                    ->name('store');
            });
    });

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to authenticated users
|
*/
Route::middleware('auth')
    ->group(function () {

        /*
        |------------------------------------------------------------------
        | Email Verification Routes
        |------------------------------------------------------------------
        */
        Route::prefix('verify-email')
            ->name('verification.')
            ->group(function () {
                Route::get('/', EmailVerificationPromptController::class)
                    ->name('notice');

                Route::get('{id}/{hash}', VerifyEmailController::class)
                    ->middleware(['signed', 'throttle:3,1'])
                    ->name('verify');
            });

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['throttle:3,1', 'honeypot'])
            ->name('verification.send');

        /*
        |------------------------------------------------------------------
        | Password Confirmation Routes
        |------------------------------------------------------------------
        */
        Route::prefix('confirm-password')
            ->name('password.')
            ->group(function () {
                Route::get('/', [ConfirmablePasswordController::class, 'show'])
                    ->name('confirm');

                Route::post('/', [ConfirmablePasswordController::class, 'store'])
                    ->middleware(['throttle:3,1', 'honeypot']);
            });

        /*
        |------------------------------------------------------------------
        | Logout Route
        |------------------------------------------------------------------
        */
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('honeypot')
            ->name('logout');
    });
