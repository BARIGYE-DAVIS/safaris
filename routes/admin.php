<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminTourController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\AccommodationTypeController;
use App\Http\Controllers\CustomTourRequestController;

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


  Route::prefix('countries')->name('countries.')->group(function () {
        Route::get('/', [CountryController::class, 'adminIndex'])->name('admin.countries.index');
        Route::get('/create', [CountryController::class, 'adminCreate'])->name('admin.countries.create');
        Route::post('/', [CountryController::class, 'adminStore'])->name('admin.countries.store');
        Route::get('/{country}/edit', [CountryController::class, 'adminEdit'])->name('admin.countries.edit');
        Route::put('/{country}', [CountryController::class, 'adminUpdate'])->name('admin.countries.update');
        Route::delete('/{country}', [CountryController::class, 'adminDestroy'])->name('admin.countries.destroy');
        Route::patch('/{country}/toggle-status', [CountryController::class, 'adminToggleStatus'])->name('admin.countries.toggle-status');
        Route::post('/bulk-delete', [CountryController::class, 'adminBulkDelete'])->name('admin.countries.bulk-delete');
        Route::post('/update-order', [CountryController::class, 'adminUpdateOrder'])->name('admin.countries.update-order');
    });

    // ========================
    // DESTINATIONS MANAGEMENT
    // ========================
    Route::prefix('destinations')->name('destinations.')->group(function () {
        Route::get('/', [DestinationController::class, 'adminIndex'])->name('admin.destinations.index');
        Route::get('/create', [DestinationController::class, 'adminCreate'])->name('admin.destinations.create');
        Route::post('/', [DestinationController::class, 'adminStore'])->name('admin.destinations.store');
        Route::get('/{destination}/edit', [DestinationController::class, 'adminEdit'])->name('admin.destinations.edit');
        Route::put('/{destination}', [DestinationController::class, 'adminUpdate'])->name('admin.destinations.update');
        Route::delete('/{destination}', [DestinationController::class, 'adminDestroy'])->name('admin.destinations.destroy');
        Route::patch('/{destination}/toggle-status', [DestinationController::class, 'adminToggleStatus'])->name('admin.destinations.toggle-status');
        Route::patch('/{destination}/toggle-popular', [DestinationController::class, 'adminTogglePopular'])->name('admin.destinations.toggle-popular');
        Route::post('/bulk-delete', [DestinationController::class, 'adminBulkDelete'])->name('admin.destinations.bulk-delete');
        Route::post('/update-order', [DestinationController::class, 'adminUpdateOrder'])->name('admin.destinations.update-order');
    });

    // ========================
    // ACTIVITY CATEGORIES MANAGEMENT
    // ========================
    Route::prefix('activity-categories')->name('activity-categories.')->group(function () {
        Route::get('/', [ActivityCategoryController::class, 'adminIndex'])->name('admin.activity-categories.index');
        Route::get('/create', [ActivityCategoryController::class, 'adminCreate'])->name('admin.activity-categories.create');
        Route::post('/', [ActivityCategoryController::class, 'adminStore'])->name('admin.activity-categories.store');
        Route::get('/{activityCategory}/edit', [ActivityCategoryController::class, 'adminEdit'])->name('admin.activity-categories.edit');
        Route::put('/{activityCategory}', [ActivityCategoryController::class, 'adminUpdate'])->name('admin.activity-categories.update');
        Route::delete('/{activityCategory}', [ActivityCategoryController::class, 'adminDestroy'])->name('admin.activity-categories.destroy');
        Route::patch('/{activityCategory}/toggle-status', [ActivityCategoryController::class, 'adminToggleStatus'])->name('admin.activity-categories.toggle-status');
        Route::post('/bulk-delete', [ActivityCategoryController::class, 'adminBulkDelete'])->name('admin.activity-categories.bulk-delete');
        Route::post('/update-order', [ActivityCategoryController::class, 'adminUpdateOrder'])->name('admin.activity-categories.update-order');
    });

    // ========================
    // ACTIVITIES MANAGEMENT
    // ========================
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'adminIndex'])->name('admin.activities.index');
        Route::get('/create', [ActivityController::class, 'adminCreate'])->name('admin.activities.create');
        Route::post('/', [ActivityController::class, 'adminStore'])->name('admin.activities.store');
        Route::get('/{activity}/edit', [ActivityController::class, 'adminEdit'])->name('admin.activities.edit');
        Route::put('/{activity}', [ActivityController::class, 'adminUpdate'])->name('admin.activities.update');
        Route::delete('/{activity}', [ActivityController::class, 'adminDestroy'])->name('admin.activities.destroy');
        Route::patch('/{activity}/toggle-status', [ActivityController::class, 'adminToggleStatus'])->name('admin.activities.toggle-status');
        Route::patch('/{activity}/toggle-popular', [ActivityController::class, 'adminTogglePopular'])->name('admin.activities.toggle-popular');
        Route::post('/bulk-delete', [ActivityController::class, 'adminBulkDelete'])->name('admin.activities.bulk-delete');
        Route::post('/update-order', [ActivityController::class, 'adminUpdateOrder'])->name('admin.activities.update-order');
    });

    // ========================
    // BUDGET CATEGORIES MANAGEMENT
    // ========================
    Route::prefix('budget-categories')->name('budget-categories.')->group(function () {
        Route::get('/', [BudgetCategoryController::class, 'adminIndex'])->name('admin.budget-categories.index');
        Route::get('/create', [BudgetCategoryController::class, 'adminCreate'])->name('admin.budget-categories.create');
        Route::post('/', [BudgetCategoryController::class, 'adminStore'])->name('admin.budget-categories.store');
        Route::get('/{budgetCategory}/edit', [BudgetCategoryController::class, 'adminEdit'])->name('admin.budget-categories.edit');
        Route::put('/{budgetCategory}', [BudgetCategoryController::class, 'adminUpdate'])->name('admin.budget-categories.update');
        Route::delete('/{budgetCategory}', [BudgetCategoryController::class, 'adminDestroy'])->name('admin.budget-categories.destroy');
        Route::patch('/{budgetCategory}/toggle-status', [BudgetCategoryController::class, 'adminToggleStatus'])->name('admin.budget-categories.toggle-status');
        Route::post('/bulk-delete', [BudgetCategoryController::class, 'adminBulkDelete'])->name('admin.budget-categories.bulk-delete');
        Route::post('/update-order', [BudgetCategoryController::class, 'adminUpdateOrder'])->name('admin.budget-categories.update-order');
    });

    // ========================
    // ACCOMMODATION TYPES MANAGEMENT
    // ========================
    Route::prefix('accommodation-types')->name('accommodation-types.')->group(function () {
        Route::get('/', [AccommodationTypeController::class, 'adminIndex'])->name('index');
        Route::get('/create', [AccommodationTypeController::class, 'adminCreate'])->name('create');
        Route::post('/', [AccommodationTypeController::class, 'adminStore'])->name('store');
        Route::get('/{accommodationType}/edit', [AccommodationTypeController::class, 'adminEdit'])->name('edit');
        Route::put('/{accommodationType}', [AccommodationTypeController::class, 'adminUpdate'])->name('update');
        Route::delete('/{accommodationType}', [AccommodationTypeController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{accommodationType}/toggle-status', [AccommodationTypeController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::post('/bulk-delete', [AccommodationTypeController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [AccommodationTypeController::class, 'adminUpdateOrder'])->name('update-order');
    });

    // ========================
    // CUSTOM TOUR REQUESTS MANAGEMENT
    // ========================
    Route::prefix('custom-tour-requests')->name('custom-tour-requests.')->group(function () {
        Route::get('/', [CustomTourRequestController::class, 'adminIndex'])->name('admin.custom-tour-requests.index');
        Route::get('/{customTourRequest}', [CustomTourRequestController::class, 'adminShow'])->name('admin.custom-tour-requests.show');
        Route::get('/{customTourRequest}/edit', [CustomTourRequestController::class, 'adminEdit'])->name('admin.custom-tour-requests.edit');
        Route::put('/{customTourRequest}', [CustomTourRequestController::class, 'adminUpdate'])->name('admin.custom-tour-requests.update');
        Route::delete('/{customTourRequest}', [CustomTourRequestController::class, 'adminDestroy'])->name('admin.custom-tour-requests.destroy');
        Route::patch('/{customTourRequest}/update-status', [CustomTourRequestController::class, 'adminUpdateStatus'])->name('admin.custom-tour-requests.update-status');
        Route::post('/{customTourRequest}/add-note', [CustomTourRequestController::class, 'adminAddNote'])->name('admin.custom-tour-requests.add-note');
        Route::post('/bulk-update-status', [CustomTourRequestController::class, 'adminBulkUpdateStatus'])->name('admin.custom-tour-requests.bulk-update-status');
        Route::post('/bulk-delete', [CustomTourRequestController::class, 'adminBulkDelete'])->name('admin.custom-tour-requests.bulk-delete');
        Route::get('/export/csv', [CustomTourRequestController::class, 'adminExport'])->name('admin.custom-tour-requests.export');
    });
