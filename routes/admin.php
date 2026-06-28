<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\AdminSpecialToursController;
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
use App\Http\Controllers\SeoPageController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityImageController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\AccommodationTypeController;
use App\Http\Controllers\CustomTourRequestController;

/**
 * Admin URL prefix
 *   /me            -> redirects to /me/login
 *   /me/login      -> admin login page
 *   /me/dashboard  -> admin dashboard (protected)
 */
$ADMIN_PREFIX = 'me';

// ========================
// ADMIN AUTHENTICATION (PUBLIC)
// ========================

Route::get($ADMIN_PREFIX, function () use ($ADMIN_PREFIX) {
    return redirect("/{$ADMIN_PREFIX}/login");
})->name('admin.entry');

Route::get("{$ADMIN_PREFIX}/login", [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post("{$ADMIN_PREFIX}/login", [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get("{$ADMIN_PREFIX}/verify", [AdminAuthController::class, 'showTwoFactorForm'])->name('admin.2fa.form');
Route::post("{$ADMIN_PREFIX}/verify", [AdminAuthController::class, 'verifyTwoFactorCode'])->name('admin.2fa.verify');
Route::post("{$ADMIN_PREFIX}/verify/resend", [AdminAuthController::class, 'resendTwoFactorCode'])->name('admin.2fa.resend');

Route::post("{$ADMIN_PREFIX}/logout", [AdminAuthController::class, 'logout'])
    ->middleware(AdminAuthenticate::class)
    ->name('admin.logout');


// ========================
// ADMIN ROUTES (PROTECTED)
// ========================
Route::middleware(AdminAuthenticate::class)
    ->prefix($ADMIN_PREFIX)
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Subscribers
    Route::get('subscribers', [SubscribersController::class, 'index'])->name('subscribers.index');

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
        Route::post('/update-order', [ActivityController::class, 'adminUpdateOrder'])->name('update-order');

        // Quick actions + images
        Route::patch('{activity}/toggle-active', [ActivityController::class, 'adminToggleActive'])->name('toggle-active');
        Route::delete('bulk-delete', [ActivityController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('{activity}/images/reorder', [ActivityImageController::class, 'reorder'])->name('images.reorder');
        Route::post('{activity}/images/upload', [ActivityImageController::class, 'upload'])->name('images.upload');

        // ========================
        // ACTIVITY OPTIONS MANAGEMENT
        // ========================
        Route::prefix('options')->name('options.')->group(function () {
            Route::get('/', [ActivityController::class, 'adminOptionsIndex'])->name('index');
            Route::post('/', [ActivityController::class, 'adminOptionsStore'])->name('store');
            Route::put('/{option}', [ActivityController::class, 'adminOptionsUpdate'])->name('update');
            Route::delete('/{option}', [ActivityController::class, 'adminOptionsDestroy'])->name('destroy');
        });
    });

    Route::delete('activity-images/{activityImage}', [ActivityImageController::class, 'destroy'])->name('activity-images.destroy');
    Route::post('activity-images/{activityImage}/set-featured', [ActivityImageController::class, 'setFeatured'])->name('activity-images.set-featured');
    Route::put('activity-images/{activityImage}', [ActivityImageController::class, 'update'])->name('activity-images.update');

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

    // ========================
    // BLOG MANAGEMENT
    // ========================
    Route::get('blogs', [BlogController::class, 'adminIndex'])->name('blogs.index');
    Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('blogs/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('blogs.toggleFeatured');
    Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.uploadImage');

    // Blog categories
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
    Route::post('blog-categories/bulk-destroy', [BlogCategoryController::class, 'bulkDestroy'])->name('blog-categories.bulk-destroy');
    Route::post('blog-categories/reorder', [BlogCategoryController::class, 'reorder'])->name('blog-categories.reorder');
    Route::get('blog-categories/api', [BlogCategoryController::class, 'apiList'])->name('blog-categories.api');

    // ========================
    // ACCOMMODATIONS MANAGEMENT
    // ========================
    Route::get('accommodations', [AccommodationController::class, 'adminIndex'])->name('accommodations.index');
    Route::get('accommodations/create', [AccommodationController::class, 'adminCreate'])->name('accommodations.create');
    Route::post('accommodations', [AccommodationController::class, 'adminStore'])->name('accommodations.store');
    Route::get('accommodations/{accommodation}/edit', [AccommodationController::class, 'adminEdit'])->name('accommodations.edit');
    Route::put('accommodations/{accommodation}', [AccommodationController::class, 'adminUpdate'])->name('accommodations.update');
    Route::delete('accommodations/{accommodation}', [AccommodationController::class, 'adminDestroy'])->name('accommodations.destroy');

    // Accommodations API
    Route::get('api/accommodations/search', [AccommodationController::class, 'apiSearch'])->name('api.accommodations.search');
    Route::get('api/accommodations/{id}', [AccommodationController::class, 'apiGetById'])->name('api.accommodations.getById');

    // ========================
    // EMAIL MANAGEMENT
    // ========================
    Route::get('emails/compose', [\App\Http\Controllers\AdminEmailController::class, 'compose'])->name('emails.compose');
    Route::post('emails/send', [\App\Http\Controllers\AdminEmailController::class, 'send'])->name('emails.send');

    // ========================
    // SPECIAL TOURS MANAGEMENT
    // ========================
    Route::prefix('special-tours')->name('special-tours.')->group(function () {
        Route::get('/', [AdminSpecialToursController::class, 'index'])->name('index');
        Route::get('/create', [AdminSpecialToursController::class, 'create'])->name('create');
        Route::post('/', [AdminSpecialToursController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminSpecialToursController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminSpecialToursController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminSpecialToursController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/activate', [AdminSpecialToursController::class, 'activate'])->name('activate');
        Route::patch('/{id}/deactivate', [AdminSpecialToursController::class, 'deactivate'])->name('deactivate');
        Route::delete('/images/{imageId}', [AdminSpecialToursController::class, 'destroyImage'])->name('images.destroy');
        Route::post('/{tourId}/images/reorder', [AdminSpecialToursController::class, 'updateImageOrder'])->name('images.reorder');
    });


    // ========================
// SEO PAGES MANAGEMENT
// ========================



Route::prefix('seo-pages')->name('seo-pages.')->group(function () {
    Route::get('/', [SeoPageController::class, 'index'])->name('index');
    Route::get('/create', [SeoPageController::class, 'create'])->name('create');
    Route::post('/', [SeoPageController::class, 'store'])->name('store');
    Route::get('/{seoPage}/edit', [SeoPageController::class, 'edit'])->name('edit');
    Route::put('/{seoPage}', [SeoPageController::class, 'update'])->name('update');
    Route::get('/{slug}', [SeoPageController::class, 'show'])->name('show');
    Route::delete('/{seoPage}', [SeoPageController::class, 'destroy'])->name('destroy');
    Route::patch('/{seoPage}/toggle-status', [SeoPageController::class, 'toggleStatus'])->name('toggle-status');
    
    // ===== MISSING ROUTES FOR ENHANCED FUNCTIONALITY =====
    
    // Auto-save draft (AJAX)
    Route::post('/auto-save', [SeoPageController::class, 'autoSave'])->name('auto-save');
    
    // Image upload for content blocks (AJAX)
    Route::post('/upload-image', [SeoPageController::class, 'uploadImage'])->name('upload-image');
    
    // Preview page (AJAX or view)
    Route::post('/preview', [SeoPageController::class, 'preview'])->name('preview');
    
    // Duplicate page
    Route::post('/{seoPage}/duplicate', [SeoPageController::class, 'duplicate'])->name('duplicate');
    
    // Bulk actions
    Route::post('/bulk-actions', [SeoPageController::class, 'bulkActions'])->name('bulk-actions');
    
    // Export page as JSON/PDF
    Route::get('/{seoPage}/export/{format}', [SeoPageController::class, 'export'])->name('export');
    
    // Page revisions
    Route::get('/{seoPage}/revisions', [SeoPageController::class, 'revisions'])->name('revisions');
    Route::post('/{seoPage}/revisions/{revision}/restore', [SeoPageController::class, 'restoreRevision'])->name('revisions.restore');
    
    // SEO analysis (AJAX)
    Route::post('/analyze-seo', [SeoPageController::class, 'analyzeSEO'])->name('analyze-seo');
    
    // Search pages for internal links (AJAX)
    Route::get('/search', [SeoPageController::class, 'searchPages'])->name('search');
    
    // Get page data for edit (AJAX)
    Route::get('/{seoPage}/data', [SeoPageController::class, 'getPageData'])->name('get-data');
    
    // Update block order (AJAX)
    Route::post('/update-block-order', [SeoPageController::class, 'updateBlockOrder'])->name('update-block-order');
    
    // API endpoints for headless CMS (optional)
    Route::get('/api/all', [SeoPageController::class, 'apiIndex'])->name('api.index');
    Route::get('/api/{slug}', [SeoPageController::class, 'apiShow'])->name('api.show');
});




});

// Admin Profile Routes
Route::prefix('profile')->group(function () {
    Route::get('/', [App\Http\Controllers\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('admin.profile.update');
});

Route::get('/explore/{slug}', [SeoPageController::class, 'show'])->name('seo-pages.show');