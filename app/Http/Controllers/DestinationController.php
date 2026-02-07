<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    /**
     * ADMIN: Display a listing of destinations
     */
    public function adminIndex(Request $request)
    {
        $query = Destination::with('country');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by popular
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'yes');
        }

        $destinations = $query->ordered()->paginate(15);
        $countries = Country::active()->ordered()->get();

        return view('admin.destinations.index', compact('destinations', 'countries'));
    }

    /**
     * ADMIN: Show the form for creating a new destination
     */
    public function adminCreate()
    {
        $countries = Country::active()->ordered()->get();
        
        return view('admin.destinations.create', compact('countries'));
    }

    /**
     * ADMIN: Store a newly created destination
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
                        ->with('success', 'Destination created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified destination
     */
    public function adminEdit(Destination $destination)
    {
        $countries = Country::active()->ordered()->get();
        
        return view('admin.destinations.edit', compact('destination', 'countries'));
    }

    /**
     * ADMIN: Update the specified destination
     */
    public function adminUpdate(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug,' . $destination->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $destination->sort_order;

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
                        ->with('success', 'Destination updated successfully!');
    }

    /**
     * ADMIN: Remove the specified destination
     */
    public function adminDestroy(Destination $destination)
    {
        // Delete image if exists
        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')
                        ->with('success', 'Destination deleted successfully!');
    }

    /**
     * ADMIN: Toggle destination active status
     */
    public function adminToggleStatus(Destination $destination)
    {
        $destination->update([
            'is_active' => !$destination->is_active
        ]);

        $status = $destination->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Destination {$status} successfully!");
    }

    /**
     * ADMIN: Toggle popular status
     */
    public function adminTogglePopular(Destination $destination)
    {
        $destination->update([
            'is_popular' => !$destination->is_popular
        ]);

        $status = $destination->is_popular ? 'marked as popular' : 'unmarked as popular';
        
        return back()->with('success', "Destination {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete destinations
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:destinations,id'
        ]);

        $destinations = Destination::whereIn('id', $request->ids)->get();

        foreach ($destinations as $destination) {
            // Delete image if exists
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
            $destination->delete();
        }

        return back()->with('success', 'Selected destinations deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:destinations,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            Destination::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of destinations
     */
    public function index(Request $request)
    {
        $query = Destination::with('country')->active();

        // Filter by country
        if ($request->filled('country')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('slug', $request->country)
                  ->orWhere('code', $request->country);
            });
        }

        // Show only popular
        if ($request->filled('popular')) {
            $query->popular();
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $destinations = $query->ordered()->paginate(12);
        $countries = Country::active()->ordered()->get();
        $popularDestinations = Destination::active()->popular()->ordered()->limit(6)->get();

        return view('destinations.index', compact('destinations', 'countries', 'popularDestinations'));
    }

    /**
     * PUBLIC: Show single destination
     */
    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)
                                  ->with('country')
                                  ->active()
                                  ->firstOrFail();

        // Get related destinations from same country
        $relatedDestinations = Destination::where('country_id', $destination->country_id)
                                         ->where('id', '!=', $destination->id)
                                         ->active()
                                         ->ordered()
                                         ->limit(3)
                                         ->get();

        return view('destinations.show', compact('destination', 'relatedDestinations'));
    }

    /**
     * API: Get destinations by country
     */
    public function getByCountry($countryId)
    {
        $destinations = Destination::where('country_id', $countryId)
                                   ->active()
                                   ->ordered()
                                   ->get();

        return response()->json($destinations);
    }

    /**
     * API: Get popular destinations
     */
    public function getPopular()
    {
        $destinations = Destination::active()
                                   ->popular()
                                   ->ordered()
                                   ->limit(6)
                                   ->get();

        return response()->json($destinations);
    }
}