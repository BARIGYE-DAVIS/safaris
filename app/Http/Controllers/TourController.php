<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class TourController extends Controller
{
    public function index(Request $request)
    {
        // Get all tours for filtering data
        $allTours = Tour::with(['itineraries', 'prices'])->get();
        
        // Extract unique values for filters from database
        $availableCategories = Tour::whereNotNull('category')
                                  ->where('category', '!=', '')
                                  ->distinct()
                                  ->pluck('category')
                                  ->filter()
                                  ->sort();
                                  
        $availableTypes = Tour::whereNotNull('type')
                             ->where('type', '!=', '')
                             ->distinct()
                             ->pluck('type')
                             ->filter()
                             ->sort();
                             
        $availableDestinations = Tour::whereNotNull('destinations')
                                    ->where('destinations', '!=', '')
                                    ->get()
                                    ->flatMap(function ($tour) {
                                        // Split destinations by comma and clean up
                                        return array_map('trim', explode(',', $tour->destinations));
                                    })
                                    ->unique()
                                    ->filter()
                                    ->sort();
                                    
        // Get duration options from actual itinerary counts
        $availableDurations = Tour::with('itineraries')
                                 ->get()
                                 ->map(function ($tour) {
                                     return $tour->itineraries->count();
                                 })
                                 ->filter()
                                 ->unique()
                                 ->sort();
                                 
        // Get price ranges from actual prices
        $priceRanges = [
            'min' => Tour::with('prices')->get()->flatMap(function ($tour) {
                return $tour->prices->pluck('price');
            })->min() ?: 0,
            'max' => Tour::with('prices')->get()->flatMap(function ($tour) {
                return $tour->prices->pluck('price');
            })->max() ?: 0,
        ];

        // Start building the query
        $query = Tour::with(['itineraries', 'prices']);
        
        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('destination')) {
            $query->where('destinations', 'like', '%' . $request->destination . '%');
        }
        
        if ($request->filled('duration')) {
            $query->whereHas('itineraries', function ($q) use ($request) {
                $q->havingRaw('COUNT(*) = ?', [$request->duration]);
            });
        }
        
        if ($request->filled('price_range')) {
            $priceRange = $request->price_range;
            if (isset($priceRanges['min']) && isset($priceRanges['max']) && $priceRanges['max'] > 0) {
                $step = ($priceRanges['max'] - $priceRanges['min']) / 4;
                $ranges = [
                    'low' => ['min' => $priceRanges['min'], 'max' => $priceRanges['min'] + $step],
                    'mid-low' => ['min' => $priceRanges['min'] + $step, 'max' => $priceRanges['min'] + ($step * 2)],
                    'mid-high' => ['min' => $priceRanges['min'] + ($step * 2), 'max' => $priceRanges['min'] + ($step * 3)],
                    'high' => ['min' => $priceRanges['min'] + ($step * 3), 'max' => $priceRanges['max']],
                ];
                
                if (isset($ranges[$priceRange])) {
                    $query->whereHas('prices', function ($q) use ($ranges, $priceRange) {
                        $q->whereBetween('price', [$ranges[$priceRange]['min'], $ranges[$priceRange]['max']]);
                    });
                }
            }
        }
        
        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->join('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                          ->orderBy('tour_prices.price', 'asc');
                    break;
                case 'price_high':
                    $query->join('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                          ->orderBy('tour_prices.price', 'desc');
                    break;
                case 'duration_short':
                    $query->withCount('itineraries')->orderBy('itineraries_count', 'asc');
                    break;
                case 'duration_long':
                    $query->withCount('itineraries')->orderBy('itineraries_count', 'desc');
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
        
        // Paginate results
        $tours = $query->paginate(12);
        
        return view('tours.index', compact(
            'tours',
            'availableCategories',
            'availableTypes', 
            'availableDestinations',
            'availableDurations',
            'priceRanges'
        ));
    }
    
    // Rest of your existing methods...
    public function show($slug)
    {
        $tour = Tour::where('slug', $slug)->with(['itineraries', 'prices', 'images'])->firstOrFail();
        
        // Get related tours
        $relatedTours = Tour::where('category', $tour->category)
                           ->where('id', '!=', $tour->id)
                           ->limit(4)
                           ->get();
        
        return view('tours.show', compact('tour', 'relatedTours'));
    }
    
    public function category($category)
    {
        $tours = Tour::where('category', $category)->paginate(12);
        $categoryName = ucfirst($category);
        
        return view('tours.category', compact('tours', 'category', 'categoryName'));
    }
}