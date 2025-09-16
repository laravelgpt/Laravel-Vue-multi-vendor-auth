<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Livewire\Profile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Routes for user settings and profile management
|
*/

Route::middleware('auth')
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {

        // Redirect to profile settings
        Route::redirect('/', '/settings/profile');

        // Profile Settings
        Route::prefix('profile')
            ->name('profile.')
            ->controller(ProfileController::class)
            ->group(function () {
                Route::get('/', 'edit')
                    ->name('edit');

                Route::patch('/', 'update')
                    ->name('update');

                Route::delete('/', 'destroy')
                    ->name('destroy');
            });

        // Password Settings
        Route::prefix('password')
            ->name('password.')
            ->controller(PasswordController::class)
            ->group(function () {
                Route::get('/', 'edit')
                    ->name('edit');

                Route::put('/', 'update')
                    ->middleware('throttle:6,1')
                    ->name('update');
            });

        // Appearance Settings
        Route::get('appearance', function () {
            return view('settings.appearance');
        })->name('appearance');
    });
