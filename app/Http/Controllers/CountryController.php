<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * ADMIN: Display a listing of countries
     */
    public function adminIndex(Request $request)
    {
        $query = Country::withCount(['destinations', 'activities']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $countries = $query->ordered()->paginate(15);

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * ADMIN: Show the form for creating a new country
     */
    public function adminCreate()
    {
        return view('admin.countries.create');
    }

    /**
     * ADMIN: Store a newly created country
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:countries,code',
            'flag_icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Country::create($validated);

        return redirect()->route('admin.countries.index')
                        ->with('success', 'Country created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified country
     */
    public function adminEdit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * ADMIN: Update the specified country
     */
    public function adminUpdate(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:countries,code,' . $country->id,
            'flag_icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $country->sort_order;

        $country->update($validated);

        return redirect()->route('admin.countries.index')
                        ->with('success', 'Country updated successfully!');
    }

    /**
     * ADMIN: Remove the specified country
     */
    public function adminDestroy(Country $country)
    {
        // Check if country has destinations or activities
        if ($country->destinations()->count() > 0) {
            return back()->with('error', 'Cannot delete country with existing destinations!');
        }

        $country->delete();

        return redirect()->route('admin.countries.index')
                        ->with('success', 'Country deleted successfully!');
    }

    /**
     * ADMIN: Toggle country active status
     */
    public function adminToggleStatus(Country $country)
    {
        $country->update([
            'is_active' => !$country->is_active
        ]);

        $status = $country->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Country {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete countries
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:countries,id'
        ]);

        Country::whereIn('id', $request->ids)
               ->whereDoesntHave('destinations')
               ->delete();

        return back()->with('success', 'Selected countries deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:countries,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            Country::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of countries (for website visitors)
     */
    public function index()
    {
        $countries = Country::active()
                           ->ordered()
                           ->withCount(['destinations' => function ($query) {
                               $query->where('is_active', true);
                           }])
                           ->get();

        return view('countries.index', compact('countries'));
    }

    /**
     * PUBLIC: Show single country with destinations
     */
    public function show(Country $country)
    {
        if (!$country->is_active) {
            abort(404);
        }

        $destinations = $country->destinations()
                               ->active()
                               ->ordered()
                               ->get();

        $activities = $country->activities()
                             ->active()
                             ->ordered()
                             ->get();

        return view('countries.show', compact('country', 'destinations', 'activities'));
    }

    /**
     * API: Get countries for AJAX requests
     */
    public function getCountries()
    {
        $countries = Country::active()->ordered()->get();
        
        return response()->json($countries);
    }

    /**
     * API: Get destinations by country for AJAX
     */
    public function getDestinationsByCountry($countryId)
    {
        $destinations = Country::findOrFail($countryId)
                               ->destinations()
                               ->active()
                               ->ordered()
                               ->get();

        return response()->json($destinations);
    }

    /**
     * API: Get activities by country for AJAX
     */
    public function getActivitiesByCountry($countryId)
    {
        $activities = Country::findOrFail($countryId)
                             ->activities()
                             ->where('is_active', true)
                             ->ordered()
                             ->get();

        return response()->json($activities);
    }
}