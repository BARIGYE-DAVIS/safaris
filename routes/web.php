<?php

use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage - redirect to tours or create a proper home page
Route::get('/', function () {
    return redirect()->route('tours.index');
})->name('home');

// Tours routes
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/category/{category}', [TourController::class, 'category'])->name('tours.category');
Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

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