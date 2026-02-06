<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminTourController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;

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

Route::prefix('admin')->group(function () {
    Route::get('/contacts', [ContactController::class, 'admin'])->name('admin.contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('admin.contacts.show');
    Route::put('/contacts/{id}/read-status', [ContactController::class, 'updateReadStatus'])->name('admin.contacts.read-status');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
    Route::post('/contacts/bulk-update', [ContactController::class, 'bulkUpdate'])->name('admin.contacts.bulk');
    Route::get('/contacts-export', [ContactController::class, 'export'])->name('admin.contacts.export');
    Route::get('/contacts-search', [ContactController::class, 'search'])->name('admin.contacts.search');

    Route::get('/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('admin.bookings.show');
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.status');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
    Route::post('/bookings/bulk-update', [BookingController::class, 'bulkUpdate'])->name('admin.bookings.bulk');
    Route::get('/bookings-export', [BookingController::class, 'export'])->name('admin.bookings.export');

    Route::get('/gallery', [GalleryController::class, 'admin'])->name('admin.gallery.index');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('admin.gallery.create');
    Route::get('/gallery/{gallery}', [GalleryController::class, 'adminShow'])->name('admin.gallery.show'); 
    Route::post('/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::get('/gallery/{gallery}/edit', [GalleryController::class, 'edit'])->name('admin.gallery.edit');
    Route::put('/gallery/{gallery}', [GalleryController::class, 'update'])->name('admin.gallery.update');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
    Route::post('/gallery/{gallery}/toggle-visibility', [GalleryController::class, 'toggleVisibility'])->name('admin.gallery.toggle-visibility');
    Route::delete('/gallery/bulk-delete', [GalleryController::class, 'bulkDelete'])->name('admin.gallery.bulk-delete');
    Route::get('/gallery-export', [GalleryController::class, 'export'])->name('admin.gallery.export');

});