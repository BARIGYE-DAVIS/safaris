<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourPrice;
use App\Models\Accommodation;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function index(Request $request)
    {
        // --- Build available filter lists efficiently ---
        $availableCategories = Tour::whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $availableTypes = Tour::whereNotNull('type')
            ->where('type', '!=', '')
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort()
            ->values();

        // destinations is a comma-separated column; pluck strings only, then explode & clean
        $destinationStrings = Tour::whereNotNull('destinations')
            ->where('destinations', '!=', '')
            ->pluck('destinations');

        $availableDestinations = collect($destinationStrings)
            ->flatMap(function ($destString) {
                return collect(explode(',', $destString))->map(fn($d) => trim($d));
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // available durations (number of itinerary days) using withCount to avoid loading all itinerary models
        $availableDurations = Tour::withCount('itineraries')
            ->get()
            ->pluck('itineraries_count')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Price ranges (min / max) from TourPrice model (faster and accurate)
        $minPrice = TourPrice::min('price') ?? 0;
        $maxPrice = TourPrice::max('price') ?? 0;
        $priceRanges = ['min' => (float) $minPrice, 'max' => (float) $maxPrice];

        // --- Build base query ---
        // select('tours.*') added so selectSub can be appended safely
        $query = Tour::with(['itineraries', 'prices'])->withCount('itineraries')->select('tours.*');

        // --- Search ---
        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('destinations', 'like', "%{$term}%");
            });
        }

        // --- Filters ---
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('destination')) {
            // simple substring match; you might normalize destinations in DB for better matching
            $query->where('destinations', 'like', '%' . $request->destination . '%');
        }

        if ($request->filled('duration')) {
            // because we used withCount('itineraries') earlier, we can filter by the computed count
            $duration = (int) $request->duration;
            $query->having('itineraries_count', $duration);
        }

        if ($request->filled('price_range') && $priceRanges['max'] > 0) {
            // Divide min-max into four buckets like before, but computed from TourPrice
            $rangeKey = $request->price_range;
            $min = $priceRanges['min'];
            $max = $priceRanges['max'];

            // avoid division by zero
            $step = $max > $min ? ($max - $min) / 4 : 0;

            $ranges = [
                'low' => ['min' => $min, 'max' => $min + $step],
                'mid-low' => ['min' => $min + $step, 'max' => $min + ($step * 2)],
                'mid-high' => ['min' => $min + ($step * 2), 'max' => $min + ($step * 3)],
                'high' => ['min' => $min + ($step * 3), 'max' => $max],
            ];

            if (isset($ranges[$rangeKey])) {
                $r = $ranges[$rangeKey];
                $query->whereHas('prices', function ($q) use ($r) {
                    $q->whereBetween('price', [$r['min'], $r['max']]);
                });
            }
        }

        // --- Sorting ---
        // For price sorting we use a subquery that selects the minimum price for the tour (min_price)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    // select subquery min price and order ascending
                    $query->selectSub(function ($sub) {
                        $sub->from('tour_prices')
                            ->selectRaw('MIN(price)')
                            ->whereColumn('tour_prices.tour_id', 'tours.id');
                    }, 'min_price');
                    $query->orderBy('min_price', 'asc');
                    break;

                case 'price_high':
                    $query->selectSub(function ($sub) {
                        $sub->from('tour_prices')
                            ->selectRaw('MIN(price)')
                            ->whereColumn('tour_prices.tour_id', 'tours.id');
                    }, 'min_price');
                    $query->orderBy('min_price', 'desc');
                    break;

                case 'duration_short':
                    $query->orderBy('itineraries_count', 'asc');
                    break;

                case 'duration_long':
                    $query->orderBy('itineraries_count', 'desc');
                    break;

                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'title_az':
                    $query->orderBy('title', 'asc');
                    break;

                case 'title_za':
                    $query->orderBy('title', 'desc');
                    break;

                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // --- Pagination (preserve filters in links) ---
        $tours = $query->paginate(12)->appends($request->except('page'));

        return view('tours.index', compact(
            'tours',
            'availableCategories',
            'availableTypes',
            'availableDestinations',
            'availableDurations',
            'priceRanges'
        ));
    }


        
public function show($slug)
{
    $tour = Tour::with([
        'itineraries' => function ($q) {
            $q->orderBy('day_number');
        },
        'itineraries.images',                            // activity / day photos
        'itineraries.accommodationRecord.images',        // ← FIXED: was 'accommodationModel'
        'prices',
        'images',
    ])->where('slug', $slug)->firstOrFail();

    $relatedTours = Tour::with(['itineraries', 'prices'])
        ->where('id', '!=', $tour->id)
        ->where(function ($q) use ($tour) {
            $q->where('category', $tour->category)
              ->orWhere('type', $tour->type);
        })
        ->latest()
        ->take(4)
        ->get();

    return view('tours.show', compact('tour', 'relatedTours'));
}             



    public function category($category)
    {
        $tours = Tour::where('category', $category)
            ->with(['itineraries', 'prices'])
            ->paginate(12);

        $categoryName = Str::title($category);

        return view('tours.category', compact('tours', 'category', 'categoryName'));
    }

    public function duration($days)
    {
        $tours = Tour::withCount('itineraries')
            ->having('itineraries_count', $days)
            ->with(['itineraries', 'prices'])
            ->paginate(12);

        $durationType = $days . ' Day' . ($days != 1 ? 's' : '');

        return view('tours.duration', compact('tours', 'days', 'durationType'));
    }

    public function budget()
    {
        $tours = \App\Models\Tour::query()
            ->where('status', 'published')
            ->where('category', 'budget')
            ->with(['images', 'prices', 'itineraries'])
            ->orderBy('title')
            ->paginate(9);

        return view('tours.budget', compact('tours'));
    }

    public function midrange()
    {
        $tours = \App\Models\Tour::query()
            ->where('status', 'published')
            ->where('category', 'middle Range')
            ->with(['images', 'prices', 'itineraries'])
            ->orderBy('title')
            ->paginate(9);

        return view('tours.midrange', compact('tours'));
    }

    public function luxury()
    {
        $tours = \App\Models\Tour::query()
            ->where('status', 'published')
            ->where('category', 'luxury')
            ->with(['images', 'prices', 'itineraries'])
            ->orderBy('title')
            ->paginate(9);

        return view('tours.luxury', compact('tours'));  
    }
}