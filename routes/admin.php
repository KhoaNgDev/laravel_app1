<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:Admin'])
    ->group(function () {


        // TODO ADMIN
        Route::get('/dashboard', [DashboardController::class, 'AdminDashboard'])
            ->name('dashboard');


        // TODO ROUTE ADMIN CONTROLLER
        Route::controller(AdminController::class)
            ->group(function () {

            Route::get('/logouts', 'AdminLogout')->name('logouts');
            Route::get('/profile', 'AdminProfile')->name('profile');
            Route::post('/profile/store', 'AdminProfileStore')->name('profile.store');

            Route::get('/password/change', 'AdminChangePassword')->name('password.change');
            Route::post('/update/password', 'AdminPasswordUpdate')->name('update.password');

        });

        // TODO ROUTE USERS CONTROLLER
        Route::controller(UserController::class)
            ->prefix('/users')
            ->name('users.')
            ->group(function () {

            Route::get('/', 'index')->name('index');
            Route::get('/list', 'UserList')->name('list');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::post('/{id}/toggle-status', 'toggleStatus')->name('toggle');
            Route::post('/{id}/soft-delete', 'softDelete')->name('softDelete');
        });


        // TODO ROUTE PRODUCTS CONTROLLER
        Route::controller(ProductController::class)
            ->prefix('/products')
            ->name('products.')
            ->group(function () {

            Route::get('/', 'index')->name('index');
            Route::get('/list', 'ProductList')->name('list');

            Route::post('/store', 'store')->name('store');

            Route::get('/edit/{product_id}', 'edit')->name('edit');
            Route::put('/update/{product_id}', 'update')->name('update');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });

        // TODO ROUTE CUSTOMER CONTROLLER
        Route::controller(CustomerController::class)
            ->prefix('/customers')
            ->name('customers.')
            ->group(function () {

            Route::get('/', 'index')->name('index');
            Route::get('/list', 'CustomerList')->name('list');

            Route::post('/store', 'store')->name('store');

            Route::put('/{id}', 'update')->name('update');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');

            Route::get('/export', 'export')->name('export');
            Route::post('/import', 'import')->name('import');

        });

    });

require __DIR__ . '/auth.php';