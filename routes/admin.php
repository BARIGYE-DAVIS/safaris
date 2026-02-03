<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminTourController;

Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    // Future admin routes can go here!
});



// All routes inside an 'admin' prefix and middleware if you wish
Route::prefix('admin')->group(function () {

    // Tour resource routes:
    Route::resource('tours', AdminTourController::class, [
        'names' => [
            'index'   => 'admin.tours.index',
            'create'  => 'admin.tours.create',
            'store'   => 'admin.tours.store',
            'show'    => 'admin.tours.show',
            'edit'    => 'admin.tours.edit',
            'update'  => 'admin.tours.update',
            'destroy' => 'admin.tours.destroy',
        ]
    ]);

});