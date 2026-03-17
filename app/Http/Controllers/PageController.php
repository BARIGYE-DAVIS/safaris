<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PageController extends Controller
{
    public function touristInformation()
    {
        return view('pages.tourist-information');
    }

    /**
     * Generate & stream the Uganda Tourist Info PDF on-the-fly.
     * No file is saved — built in memory and sent directly to the client.
     *
     * Requires:  composer require barryvdh/laravel-dompdf
     */
    public function downloadTouristInfoPdf()
    {
        $pdf = Pdf::loadView('pages.tourist-information-pdf')
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Calm-Africa-Safaris-Uganda-Tourist-Guide.pdf');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
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

    public function sitemap()
    {
        return view('pages.sitemap');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}