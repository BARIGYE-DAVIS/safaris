<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicPageVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // Let the request run first so your page loads even if logging fails
        $response = $next($request);

        // Only track normal page views (GET HTML requests)
        if (!$request->isMethod('GET')) {
            return $response;
        }

        // Don't track admin pages (and anything else you want excluded)
        if (
            $request->is('admin') ||
            $request->is('admin/*') ||
            $request->is('api/*') ||
            $request->is('storage/*') ||
            $request->is('build/*') ||
            $request->is('livewire/*') ||
            $request->is('sanctum/*')
        ) {
            return $response;
        }

        // Optional: skip common auth pages (uncomment if you want)
        // if ($request->routeIs('login', 'register', 'password.*')) {
        //     return $response;
        // }

        // Optional: skip non-HTML responses
        $contentType = $response->headers->get('Content-Type', '');
        if ($contentType && !str_contains($contentType, 'text/html')) {
            return $response;
        }

        try {
            $visit = PageVisit::create([
                'url'        => $request->fullUrl(),
                'page_title' => null, // we'll fill this later (Step: JS or Blade)
                'ip_address' => $request->ip() ?? '0.0.0.0',
                'country'    => null, // we'll fill this later (GeoIP step)
                'city'       => null, // we'll fill this later (GeoIP step)
                'user_agent' => $request->userAgent(),
                'referrer'   => $request->headers->get('referer'),
                'user_id'    => auth()->id(),
                'session_id' => $request->session()->getId(),
                'time_on_page' => null, // Step later
            ]);

            // Make it available to Blade so JS can send time-on-page later
            app()->instance('pageVisitId', $visit->id);
        } catch (\Throwable $e) {
            // Don't break the site if analytics fails
        }

        return $response;
    }
}