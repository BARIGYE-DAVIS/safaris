<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        // Get unique categories and types from database
        $categories = Tour::distinct()->pluck('category')->filter();
        $types = Tour::distinct()->pluck('type')->filter();
        
        // Build query
        $query = Tour::with(['itineraries', 'prices']);
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Sorting
        switch ($request->sort) {
            case 'price_low':
                $query->leftJoin('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                      ->orderBy('tour_prices.price', 'asc');
                break;
            case 'price_high':
                $query->leftJoin('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                      ->orderBy('tour_prices.price', 'desc');
                break;
            case 'duration':
                $query->withCount('itineraries')->orderBy('itineraries_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $tours = $query->paginate(9);
        
        return view('tours.index', compact('tours', 'categories', 'types'));
    }
    
    public function show($slug)
    {
        $tour = Tour::with(['itineraries', 'prices', 'images'])
                   ->where('slug', $slug)
                   ->firstOrFail();
        
        return view('tours.show', compact('tour'));
    }
}