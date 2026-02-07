<?php

use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\AccommodationTypeController;
use App\Http\Controllers\CustomTourRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage - redirect to tours or create a proper home page
Route::get('/', function () {
    return view('index');
})->name('index');

// Tours routes
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/category/{category}', [TourController::class, 'category'])->name('tours.category');
Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/success', [ContactController::class, 'success'])->name('contact.success');

// About
Route::get('/about', [PageController::class, 'about'])->name('about');

// Destinations
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destination');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Legal Pages
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-of-service', [PageController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('cookie-policy');
Route::get('/refund-policy', [PageController::class, 'refundPolicy'])->name('refund-policy');

// Sitemap
Route::get('/sitemap', [PageController::class, 'sitemap'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Booking Routes (FIXED)
|--------------------------------------------------------------------------
*/

// Create booking page
Route::get('/book-now', [BookingController::class, 'create'])->name('booking.create');

// Submit booking request (AJAX from tour details page)
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store'); // ← FIXED: singular URL

// View booking confirmation (customer can see their booking)
Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show'); // ← FIXED: singular URL

// Booking success page
Route::get('/booking-success', function () {
    return view('booking-success');
})->name('booking.success');

/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------
*/

// Test mail route (remove in production)
Route::get('/test-mail/{email}', function ($email) {
    try {
        \Illuminate\Support\Facades\Mail::send('emails.test-mail', [], function ($message) use ($email) {
            $message->to($email)->subject('Safari Uganda - Mail Test');
        });
        return 'Test email sent to ' . $email . '. Check your inbox!';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
})->where('email', '.*');


// ========================
// COUNTRIES
// ========================
Route::prefix('countries')->name('countries.')->group(function () {
    Route::get('/', [CountryController::class, 'index'])->name('index');
    Route::get('/{country:code}', [CountryController::class, 'show'])->name('show');
});

// ========================
// DESTINATIONS
// ========================
Route::prefix('destinations')->name('destinations.')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('index');
    Route::get('/{slug}', [DestinationController::class, 'show'])->name('show');
});

// ========================
// ACTIVITY CATEGORIES
// ========================
Route::prefix('activity-categories')->name('activity-categories.')->group(function () {
    Route::get('/', [ActivityCategoryController::class, 'index'])->name('index');
    Route::get('/{activityCategory:id}', [ActivityCategoryController::class, 'show'])->name('show');
});

// ========================
// ACTIVITIES
// ========================
Route::prefix('activities')->name('activities.')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    Route::get('/{slug}', [ActivityController::class, 'show'])->name('show');
});

// ========================
// BUDGET CATEGORIES
// ========================
Route::prefix('budget-categories')->name('budget-categories.')->group(function () {
    Route::get('/', [BudgetCategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [BudgetCategoryController::class, 'show'])->name('show');
});

// ========================
// ACCOMMODATION TYPES
// ========================
Route::prefix('accommodation-types')->name('accommodation-types.')->group(function () {
    Route::get('/', [AccommodationTypeController::class, 'index'])->name('index');
    Route::get('/{accommodationType:id}', [AccommodationTypeController::class, 'show'])->name('show');
});

// ========================
// CUSTOM TOUR REQUESTS
// ========================
Route::prefix('custom-tour-request')->name('custom-tour-requests.')->group(function () {
    Route::get('/', [CustomTourRequestController::class, 'create'])->name('create');
    Route::post('/', [CustomTourRequestController::class, 'store'])->name('store');
    Route::get('/success', [CustomTourRequestController::class, 'success'])->name('success');
    
    // Track request
    Route::get('/track', [CustomTourRequestController::class, 'track'])->name('track');
    Route::post('/track', [CustomTourRequestController::class, 'track'])->name('track.submit');
});

// ========================
// PLAN YOUR TRIP (Alternative route for custom tour request)
// ========================
Route::get('/plan-your-trip', [CustomTourRequestController::class, 'create'])->name('plan-trip');

// ========================
// API ROUTES (Public - For AJAX)
// ========================
Route::prefix('api')->name('api.')->group(function () {
    
    // Countries API
    Route::get('/countries', [CountryController::class, 'getCountries'])->name('countries.all');
    Route::get('/countries/{countryId}/destinations', [CountryController::class, 'getDestinationsByCountry'])->name('countries.destinations');
    Route::get('/countries/{countryId}/activities', [CountryController::class, 'getActivitiesByCountry'])->name('countries.activities');
    
    // Destinations API
    Route::get('/destinations/country/{countryId}', [DestinationController::class, 'getByCountry'])->name('destinations.by-country');
    Route::get('/destinations/popular', [DestinationController::class, 'getPopular'])->name('destinations.popular');
    
    // Activity Categories API
    Route::get('/activity-categories', [ActivityCategoryController::class, 'getCategories'])->name('activity-categories.all');
    Route::get('/activity-categories/{categoryId}/activities', [ActivityCategoryController::class, 'getActivitiesByCategory'])->name('activity-categories.activities');
    
    // Activities API
    Route::get('/activities/country/{countryId}', [ActivityController::class, 'getByCountry'])->name('activities.by-country');
    Route::get('/activities/category/{categoryId}', [ActivityController::class, 'getByCategory'])->name('activities.by-category');
    Route::get('/activities/popular', [ActivityController::class, 'getPopular'])->name('activities.popular');
    
    // Budget Categories API
    Route::get('/budget-categories', [BudgetCategoryController::class, 'getCategories'])->name('budget-categories.all');
    Route::get('/budget-categories/{slug}', [BudgetCategoryController::class, 'getBySlug'])->name('budget-categories.by-slug');
    
    // Accommodation Types API
    Route::get('/accommodation-types', [AccommodationTypeController::class, 'getTypes'])->name('accommodation-types.all');
    Route::get('/accommodation-types/{id}', [AccommodationTypeController::class, 'getById'])->name('accommodation-types.by-id');
    
    // Custom Tour Request API
    Route::get('/custom-tour-request/destinations/{countryId}', [CustomTourRequestController::class, 'getDestinationsByCountry'])->name('custom-tour-requests.destinations');
    Route::get('/custom-tour-request/activities/{countryId}', [CustomTourRequestController::class, 'getActivitiesByCountry'])->name('custom-tour-requests.activities');
});

// ========================
// SEARCH (Global)
// ========================
Route::get('/search', function () {
    return view('search');
})->name('search');



require __DIR__ . '/admin.php';