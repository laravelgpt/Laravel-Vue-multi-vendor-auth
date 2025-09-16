<?php

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Technician\PartController as TechnicianPartController;
use App\Http\Controllers\Technician\RepairController as TechnicianRepairController;
use App\Http\Controllers\Wholeseller\CustomerController as WholesellerCustomerController;
use App\Http\Controllers\Wholeseller\OrderController as WholesellerOrderController;
use App\Http\Controllers\Wholeseller\ProductController as WholesellerProductController;
use App\Http\Livewire\Admin\Dashboard as AdminDashboard;
use App\Http\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Technician\Dashboard as TechnicianDashboard;
use App\Http\Livewire\Wholeseller\Dashboard as WholesellerDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for admin users with admin middleware and proper prefix
|
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', AdminDashboard::class)
            ->name('dashboard');

        // Admin User Management
        Route::resource('users', UserController::class)
            ->except(['show'])
            ->names([
                'index' => 'users.index',
                'create' => 'users.create',
                'store' => 'users.store',
                'edit' => 'users.edit',
                'update' => 'users.update',
                'destroy' => 'users.destroy',
            ]);

        // Admin Profile Management
        Route::prefix('profile')
            ->name('profile.')
            ->controller(AdminProfileController::class)
            ->group(function () {
                Route::get('/', function () {
                    return view('admin.profile');
                })->name('edit');

                Route::put('/', 'update')
                    ->name('update');

                Route::put('/password', 'updatePassword')
                    ->middleware('throttle:6,1')
                    ->name('password.update');
            });
    });

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes for authenticated users with proper middleware and grouping
|
*/
Route::middleware(['auth'])
    ->group(function () {

        // Customer Dashboard
        Route::prefix('customer')
            ->name('customer.')
            ->middleware('role:customer')
            ->group(function () {
                Route::get('/dashboard', CustomerDashboard::class)->name('dashboard');

                // Order Management
                Route::resource('orders', CustomerOrderController::class)
                    ->names([
                        'index' => 'orders.index',
                        'create' => 'orders.create',
                        'store' => 'orders.store',
                        'show' => 'orders.show',
                        'edit' => 'orders.edit',
                        'update' => 'orders.update',
                        'destroy' => 'orders.destroy',
                    ]);
            });

        // Legacy customer dashboard route
        Route::get('/dashboard', CustomerDashboard::class)
            ->name('dashboard')
            ->middleware('role:customer');

        // Wholeseller Dashboard
        Route::prefix('wholeseller')
            ->name('wholeseller.')
            ->middleware('role:wholeseller')
            ->group(function () {
                Route::get('/dashboard', WholesellerDashboard::class)->name('dashboard');

                // Product Management
                Route::resource('products', WholesellerProductController::class)
                    ->names([
                        'index' => 'products.index',
                        'create' => 'products.create',
                        'store' => 'products.store',
                        'show' => 'products.show',
                        'edit' => 'products.edit',
                        'update' => 'products.update',
                        'destroy' => 'products.destroy',
                    ]);

                // Order Management
                Route::resource('orders', WholesellerOrderController::class)
                    ->names([
                        'index' => 'orders.index',
                        'create' => 'orders.create',
                        'store' => 'orders.store',
                        'show' => 'orders.show',
                        'edit' => 'orders.edit',
                        'update' => 'orders.update',
                        'destroy' => 'orders.destroy',
                    ]);

                // Customer Management
                Route::resource('customers', WholesellerCustomerController::class)
                    ->names([
                        'index' => 'customers.index',
                        'create' => 'customers.create',
                        'store' => 'customers.store',
                        'show' => 'customers.show',
                        'edit' => 'customers.edit',
                        'update' => 'customers.update',
                        'destroy' => 'customers.destroy',
                    ]);
            });

        // Technician Dashboard
        Route::prefix('technician')
            ->name('technician.')
            ->middleware('role:technician')
            ->group(function () {
                Route::get('/dashboard', TechnicianDashboard::class)->name('dashboard');

                // Repair Management
                Route::resource('repairs', TechnicianRepairController::class)
                    ->names([
                        'index' => 'repairs.index',
                        'create' => 'repairs.create',
                        'store' => 'repairs.store',
                        'show' => 'repairs.show',
                        'edit' => 'repairs.edit',
                        'update' => 'repairs.update',
                        'destroy' => 'repairs.destroy',
                    ]);

                // Active Repairs (filtered view)
                Route::get('/active-repairs', function () {
                    return view('technician.active-repairs');
                })->name('active-repairs');

                // Repair History (filtered view)
                Route::get('/history', function () {
                    return view('technician.history');
                })->name('history');

                // Parts Management
                Route::resource('parts', TechnicianPartController::class)
                    ->names([
                        'index' => 'parts.index',
                        'create' => 'parts.create',
                        'store' => 'parts.store',
                        'show' => 'parts.show',
                        'edit' => 'parts.edit',
                        'update' => 'parts.update',
                        'destroy' => 'parts.destroy',
                    ]);

                // Tools Management
                Route::get('/tools', function () {
                    return view('technician.tools');
                })->name('tools');

                // Reports
                Route::get('/reports', function () {
                    return view('technician.reports');
                })->name('reports');
            });

        // User Profile Management (available to all authenticated users)
        Route::prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('/', Profile::class)
                    ->name('edit');
            });
    });

// Include Authentication Routes
require __DIR__.'/auth.php';

// Include Settings Routes
require __DIR__.'/settings.php';
