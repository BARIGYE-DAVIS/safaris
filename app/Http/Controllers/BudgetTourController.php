<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetTourController extends Controller
{
    /**
     * Display a paginated, SEO-friendly listing of budget tours.
     *
     * This version uses the existing tour_prices table (columns: tour_id, group_size, price)
     * and computes the minimum price per tour (MIN(price)) via a derived table, then joins it
     * into the tours query. No new columns or assumptions are added.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Derived table: minimum price per tour from the existing tour_prices table
        $priceSub = DB::table('tour_prices')
            ->select('tour_id', DB::raw('MIN(price) as min_price'))
            ->groupBy('tour_id');

        // Base query: published budget tours (uses only tours columns that already exist)
        $query = Tour::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->where('category', 'budget')
                  ->orWhere('type', 'budget');
            });

        // Left join the min-price subquery (alias "tp") so tours without prices still appear
        $query = $query->leftJoinSub($priceSub, 'tp', function ($join) {
                $join->on('tp.tour_id', '=', 'tours.id');
            })
            ->select('tours.*', 'tp.min_price');

        // Order: cheapest price first. Tours without prices appear after priced tours.
        // (tp.min_price IS NULL) evaluates to 0/1 — puts non-null (0) before null (1)
        $query = $query->orderByRaw('(tp.min_price IS NULL), tp.min_price ASC, tours.id ASC');

        // Paginate and preserve query string
        $tours = $query->paginate(12)->withQueryString();

        // SEO metadata (adjust wording if you want)
        $seo = [
            'title'      => 'Affordable Budget Safaris & Tours in East Africa',
            'description'=> 'Discover our best-value budget safari holidays across Kenya, Tanzania and Uganda. Affordable wildlife tours and curated safari deals designed for budget travelers.',
            'canonical'  => route('budget-tours.index'),
            'og_image'   => asset('images/seo/budget-safari.jpg'),
            'keywords'   => implode(', ', [
                'budget safaris',
                'affordable safari',
                'cheap safaris',
                'East Africa tours',
                'budget travel',
            ]),
        ];

        return view('tours.budget-index', compact('tours'));
    }
}