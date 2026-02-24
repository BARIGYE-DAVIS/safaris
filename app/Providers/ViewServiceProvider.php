<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Destination;
use App\Models\Activity;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Compose the destinations partial so it always has $destinations
        View::composer('partials.destinations-carousel', function ($view) {
            $destinations = Destination::where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $view->with('destinations', $destinations);
        });

        // Compose the activities partial: active activities ordered by name
        View::composer('partials.activities-carousel', function ($view) {
            $activities = Activity::where('is_active', 1)
                ->orderBy('name', 'asc')   // order by name as requested
                ->take(12)                 // fetch a sensible number for carousel
                ->get()
                ->unique('id')             // guard against duplicate rows
                ->values();                // reindex collection

            $view->with('activities', $activities);
        });

        // If you want it on multiple views, use an array:
        // View::composer(['index', 'partials.destinations-carousel'], function ($view) { ... });
    }
}