<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

// Models used by the dynamic sitemap
use App\Models\Tour;
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Activity;
use App\Models\Country;
use App\Models\Gallery;

class PageController extends Controller
{
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function touristInformation()
    {
        return view('pages.tourist-information');
    }

    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    public function refundPolicy()
    {
        return view('pages.refund-policy');
    }

    public function cookiePolicy()
    {
        return view('pages.cookie-policy');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Dynamic XML sitemap
     */
    public function sitemap()
    {
        $tours = Tour::query()
            ->when(Schema::hasColumn('tours', 'status'), fn ($q) => $q->where('status', 'published'))
            ->latest('updated_at')
            ->get();

        $blogs = Blog::query()
            ->when(Schema::hasColumn('blogs', 'status'), fn ($q) => $q->where('status', 'published'))
            ->when(Schema::hasColumn('blogs', 'published_at'), fn ($q) => $q->where('published_at', '<=', now()))
            ->latest('updated_at')
            ->get();

        $destinations = Destination::query()
            ->when(Schema::hasColumn('destinations', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->latest('updated_at')
            ->get();

        $activities = Activity::query()
            ->when(Schema::hasColumn('activities', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->latest('updated_at')
            ->get();

        $countries = Country::query()
            ->when(Schema::hasColumn('countries', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->latest('updated_at')
            ->get();

        $galleries = Gallery::query()
            ->when(Schema::hasColumn('galleries', 'is_visible'), fn ($q) => $q->where('is_visible', true))
            ->latest('updated_at')
            ->get();

        return response()
            ->view('sitemap', compact('tours', 'blogs', 'destinations', 'activities', 'countries', 'galleries'))
            ->header('Content-Type', 'text/xml');
    }

    public function downloadTouristInfoPdf()
    {
        // Option A: Store the PDF in storage/app/public and run php artisan storage:link
        $pdfPath = storage_path('app/public/tourist_information.pdf');

        // Option B: Or keep it directly in public/
        // $pdfPath = public_path('downloads/tourist_information.pdf');

        if (!file_exists($pdfPath)) {
            abort(404, 'PDF not found. Please contact us.');
        }

        return response()->download(
            $pdfPath,
            'Calm-Africa-Safaris-Uganda-Tourist-Guide.pdf',
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Calm-Africa-Safaris-Uganda-Tourist-Guide.pdf"',
            ]
        );
    }

    public function viewTouristInfoPdf()
    {
        $pdfPath = storage_path('app/public/tourist_information.pdf');

        if (!file_exists($pdfPath)) {
            abort(404);
        }

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}