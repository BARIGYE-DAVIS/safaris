<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminTourController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityImageController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\AccommodationTypeController;
use App\Http\Controllers\CustomTourRequestController;

// ========================
// ADMIN AUTHENTICATION
// ========================
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/subscribers', [SubscribersController::class, 'index'])->name('admin.subscribers.index');
});


// ========================
// ADMIN ROUTES (All grouped under 'admin' prefix)
// ========================
Route::prefix('admin')->name('admin.')->group(function () {

    // ========================
    // TOURS MANAGEMENT
    // ========================
    Route::resource('tours', AdminTourController::class)->names([
        'index'   => 'tours.index',
        'create'  => 'tours.create',
        'store'   => 'tours.store',
        'show'    => 'tours.show',
        'edit'    => 'tours.edit',
        'update'  => 'tours.update',
        'destroy' => 'tours.destroy',
    ]);

    // ========================
    // CONTACTS MANAGEMENT
    // ========================
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'admin'])->name('index');
        Route::get('/{id}', [ContactController::class, 'show'])->name('show');
        Route::put('/{id}/read-status', [ContactController::class, 'updateReadStatus'])->name('read-status');
        Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [ContactController::class, 'bulkUpdate'])->name('bulk');
        Route::get('/export/csv', [ContactController::class, 'export'])->name('export');
        Route::get('/search', [ContactController::class, 'search'])->name('search');
    });

    // ========================
    // BOOKINGS MANAGEMENT
    // ========================
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::put('/{id}/status', [BookingController::class, 'updateStatus'])->name('status');
        Route::delete('/{id}', [BookingController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [BookingController::class, 'bulkUpdate'])->name('bulk');
        Route::get('/export/csv', [BookingController::class, 'export'])->name('export');
    });

    // ========================
    // GALLERY MANAGEMENT
    // ========================
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [GalleryController::class, 'admin'])->name('index');
        Route::get('/create', [GalleryController::class, 'create'])->name('create');
        Route::post('/', [GalleryController::class, 'store'])->name('store');
        Route::get('/{gallery}', [GalleryController::class, 'adminShow'])->name('show');
        Route::get('/{gallery}/edit', [GalleryController::class, 'edit'])->name('edit');
        Route::put('/{gallery}', [GalleryController::class, 'update'])->name('update');
        Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
        Route::post('/{gallery}/toggle-visibility', [GalleryController::class, 'toggleVisibility'])->name('toggle-visibility');
        Route::post('/bulk-delete', [GalleryController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export/csv', [GalleryController::class, 'export'])->name('export');
    });

    // ========================
    // COUNTRIES MANAGEMENT
    // ========================
    Route::prefix('countries')->name('countries.')->group(function () {
        Route::get('/', [CountryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [CountryController::class, 'adminCreate'])->name('create');
        Route::post('/', [CountryController::class, 'adminStore'])->name('store');
        Route::get('/{country}/edit', [CountryController::class, 'adminEdit'])->name('edit');
        Route::put('/{country}', [CountryController::class, 'adminUpdate'])->name('update');
        Route::delete('/{country}', [CountryController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{country}/toggle-status', [CountryController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::post('/bulk-delete', [CountryController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [CountryController::class, 'adminUpdateOrder'])->name('update-order');
    });

    // ========================
    // DESTINATIONS MANAGEMENT
    // ========================
    Route::prefix('destinations')->name('destinations.')->group(function () {
        Route::get('/', [DestinationController::class, 'adminIndex'])->name('index');
        Route::get('/create', [DestinationController::class, 'adminCreate'])->name('create');
        Route::post('/', [DestinationController::class, 'adminStore'])->name('store');
        Route::get('/{destination}/edit', [DestinationController::class, 'adminEdit'])->name('edit');
        Route::put('/{destination}', [DestinationController::class, 'adminUpdate'])->name('update');
        Route::delete('/{destination}', [DestinationController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{destination}/toggle-status', [DestinationController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::patch('/{destination}/toggle-popular', [DestinationController::class, 'adminTogglePopular'])->name('toggle-popular');
        Route::post('/bulk-delete', [DestinationController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [DestinationController::class, 'adminUpdateOrder'])->name('update-order');
    });

    // ========================
    // ACTIVITY CATEGORIES MANAGEMENT
    // ========================
    Route::prefix('activity-categories')->name('activity-categories.')->group(function () {
        Route::get('/', [ActivityCategoryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ActivityCategoryController::class, 'adminCreate'])->name('create');
        Route::post('/', [ActivityCategoryController::class, 'adminStore'])->name('store');
        Route::get('/{activityCategory}/edit', [ActivityCategoryController::class, 'adminEdit'])->name('edit');
        Route::put('/{activityCategory}', [ActivityCategoryController::class, 'adminUpdate'])->name('update');
        Route::delete('/{activityCategory}', [ActivityCategoryController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{activityCategory}/toggle-status', [ActivityCategoryController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::post('/bulk-delete', [ActivityCategoryController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [ActivityCategoryController::class, 'adminUpdateOrder'])->name('update-order');
    });

    // ========================
    // ACTIVITIES MANAGEMENT
    // ========================
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ActivityController::class, 'adminCreate'])->name('create');
        Route::post('/', [ActivityController::class, 'adminStore'])->name('store');
        Route::get('/{activity}/edit', [ActivityController::class, 'adminEdit'])->name('edit');
        Route::put('/{activity}', [ActivityController::class, 'adminUpdate'])->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{activity}/toggle-status', [ActivityController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::patch('/{activity}/toggle-popular', [ActivityController::class, 'adminTogglePopular'])->name('toggle-popular');
       // Route::post('/bulk-delete', [ActivityController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [ActivityController::class, 'adminUpdateOrder'])->name('update-order');
    });

    // ========================
    // BUDGET CATEGORIES MANAGEMENT
    // ========================
    Route::prefix('budget-categories')->name('budget-categories.')->group(function () {
        Route::get('/', [BudgetCategoryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [BudgetCategoryController::class, 'adminCreate'])->name('create');
        Route::post('/', [BudgetCategoryController::class, 'adminStore'])->name('store');
        Route::get('/{budgetCategory}/edit', [BudgetCategoryController::class, 'adminEdit'])->name('edit');
        Route::put('/{budgetCategory}', [BudgetCategoryController::class, 'adminUpdate'])->name('update');
        Route::delete('/{budgetCategory}', [BudgetCategoryController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{budgetCategory}/toggle-status', [BudgetCategoryController::class, 'adminToggleStatus'])->name('toggle-status');
        Route::post('/bulk-delete', [BudgetCategoryController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::post('/update-order', [BudgetCategoryController::class, 'adminUpdateOrder'])->name('update-order');
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
        Route::get('/', [CustomTourRequestController::class, 'adminIndex'])->name('index');
        Route::get('/{customTourRequest}', [CustomTourRequestController::class, 'adminShow'])->name('show');
        Route::get('/{customTourRequest}/edit', [CustomTourRequestController::class, 'adminEdit'])->name('edit');
        Route::put('/{customTourRequest}', [CustomTourRequestController::class, 'adminUpdate'])->name('update');
        Route::delete('/{customTourRequest}', [CustomTourRequestController::class, 'adminDestroy'])->name('destroy');
        Route::patch('/{customTourRequest}/update-status', [CustomTourRequestController::class, 'adminUpdateStatus'])->name('update-status');
        Route::post('/{customTourRequest}/add-note', [CustomTourRequestController::class, 'adminAddNote'])->name('add-note');
        Route::post('/bulk-update-status', [CustomTourRequestController::class, 'adminBulkUpdateStatus'])->name('bulk-update-status');
        Route::post('/bulk-delete', [CustomTourRequestController::class, 'adminBulkDelete'])->name('bulk-delete');
        Route::get('/export/csv', [CustomTourRequestController::class, 'adminExport'])->name('export');
    });

});



// Admin Activity Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Activity Quick Actions (NEW)
    Route::patch('activities/{activity}/toggle-active', [ActivityController::class, 'adminToggleActive'])->name('activities.toggle-active');
    Route::patch('activities/{activity}/toggle-popular', [ActivityController::class, 'adminTogglePopular'])->name('activities.toggle-popular');
    Route::delete('activities/bulk-delete', [ActivityController::class, 'bulkDelete'])->name('activities.bulk-delete');
    
    // Activity Image Management (NEW)
    Route::delete('activity-images/{activityImage}', [ActivityImageController::class, 'destroy'])->name('activity-images.destroy');
    Route::post('activity-images/{activityImage}/set-featured', [ActivityImageController::class, 'setFeatured'])->name('activity-images.set-featured');
    Route::put('activity-images/{activityImage}', [ActivityImageController::class, 'update'])->name('activity-images.update');
    Route::post('activities/{activity}/images/reorder', [ActivityImageController::class, 'reorder'])->name('activities.images.reorder');
    Route::post('activities/{activity}/images/upload', [ActivityImageController::class, 'upload'])->name('activities.images.upload');
    
});

// Admin blog listing
Route::get('/admin/blogs', [BlogController::class, 'adminIndex'])->name('admin.blogs.index');

// Create new blog
Route::get('/admin/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');

// Edit/update blog
Route::get('/admin/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('/admin/blogs/{blog}', [BlogController::class, 'update'])->name('admin.blogs.update');

// Delete blog
Route::delete('/admin/blogs/{blog}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');

// Toggle featured status (AJAX)
Route::post('/admin/blogs/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('admin.blogs.toggleFeatured');

// Upload inline images for editor
Route::post('/admin/blogs/upload-image', [BlogController::class, 'uploadImage'])->name('admin.blogs.uploadImage');


Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Resourceful routes for blog categories (index, create, store, edit, update, destroy)
        Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

        // Bulk delete selected categories
        Route::post('blog-categories/bulk-destroy', [BlogCategoryController::class, 'bulkDestroy'])
            ->name('blog-categories.bulk-destroy');

        // Reorder categories (expects JSON payload)
        Route::post('blog-categories/reorder', [BlogCategoryController::class, 'reorder'])
            ->name('blog-categories.reorder');

        // API endpoint for select inputs (e.g. select2) - returns id, name, slug
        Route::get('blog-categories/api', [BlogCategoryController::class, 'apiList'])
            ->name('blog-categories.api');
    });

    // Admin (you may protect these with middleware('auth', 'admin'))
Route::get('/admin/accommodations', [AccommodationController::class, 'adminIndex'])->name('admin.accommodations.index');
Route::get('/admin/accommodations/create', [AccommodationController::class, 'adminCreate'])->name('admin.accommodations.create');
Route::post('/admin/accommodations', [AccommodationController::class, 'adminStore'])->name('admin.accommodations.store');
Route::get('/admin/accommodations/{accommodation}/edit', [AccommodationController::class, 'adminEdit'])->name('admin.accommodations.edit');
Route::put('/admin/accommodations/{accommodation}', [AccommodationController::class, 'adminUpdate'])->name('admin.accommodations.update');
Route::delete('/admin/accommodations/{accommodation}', [AccommodationController::class, 'adminDestroy'])->name('admin.accommodations.destroy');