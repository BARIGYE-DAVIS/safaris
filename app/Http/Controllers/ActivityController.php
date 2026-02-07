<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * ADMIN: Display a listing of activities
     */
    public function adminIndex(Request $request)
    {
        $query = Activity::with(['category', 'countries']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->whereHas('countries', function ($q) use ($request) {
                $q->where('countries.id', $request->country_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by popular
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'yes');
        }

        $activities = $query->ordered()->paginate(15);
        $categories = ActivityCategory::active()->ordered()->get();
        $countries = Country::active()->ordered()->get();

        return view('admin.activities.index', compact('activities', 'categories', 'countries'));
    }

    /**
     * ADMIN: Show the form for creating a new activity
     */
    public function adminCreate()
    {
        $categories = ActivityCategory::active()->ordered()->get();
        $countries = Country::active()->ordered()->get();
        
        return view('admin.activities.create', compact('categories', 'countries'));
    }

    /**
     * ADMIN: Store a newly created activity
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:activity_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activities,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'countries' => 'nullable|array',
            'countries.*' => 'exists:countries,id',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('activities/icons', 'public');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Create activity
        $activity = Activity::create($validated);

        // Attach countries if selected
        if ($request->filled('countries')) {
            $activity->countries()->attach($request->countries, ['is_available' => true]);
        }

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified activity
     */
    public function adminEdit(Activity $activity)
    {
        $categories = ActivityCategory::active()->ordered()->get();
        $countries = Country::active()->ordered()->get();
        $selectedCountries = $activity->countries->pluck('id')->toArray();
        
        return view('admin.activities.edit', compact('activity', 'categories', 'countries', 'selectedCountries'));
    }

    /**
     * ADMIN: Update the specified activity
     */
    public function adminUpdate(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:activity_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activities,slug,' . $activity->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'countries' => 'nullable|array',
            'countries.*' => 'exists:countries,id',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($activity->icon) {
                Storage::disk('public')->delete($activity->icon);
            }
            $validated['icon'] = $request->file('icon')->store('activities/icons', 'public');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $activity->sort_order;

        // Update activity
        $activity->update($validated);

        // Sync countries
        if ($request->filled('countries')) {
            $activity->countries()->sync($request->countries);
        } else {
            $activity->countries()->detach();
        }

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity updated successfully!');
    }

    /**
     * ADMIN: Remove the specified activity
     */
    public function adminDestroy(Activity $activity)
    {
        // Delete icon if exists
        if ($activity->icon) {
            Storage::disk('public')->delete($activity->icon);
        }

        // Delete image if exists
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        // Detach countries
        $activity->countries()->detach();

        $activity->delete();

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity deleted successfully!');
    }

    /**
     * ADMIN: Toggle activity active status
     */
    public function adminToggleStatus(Activity $activity)
    {
        $activity->update([
            'is_active' => !$activity->is_active
        ]);

        $status = $activity->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Activity {$status} successfully!");
    }

    /**
     * ADMIN: Toggle popular status
     */
    public function adminTogglePopular(Activity $activity)
    {
        $activity->update([
            'is_popular' => !$activity->is_popular
        ]);

        $status = $activity->is_popular ? 'marked as popular' : 'unmarked as popular';
        
        return back()->with('success', "Activity {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete activities
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activities,id'
        ]);

        $activities = Activity::whereIn('id', $request->ids)->get();

        foreach ($activities as $activity) {
            // Delete icon if exists
            if ($activity->icon) {
                Storage::disk('public')->delete($activity->icon);
            }
            // Delete image if exists
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            // Detach countries
            $activity->countries()->detach();
            $activity->delete();
        }

        return back()->with('success', 'Selected activities deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:activities,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            Activity::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of activities
     */
    public function index(Request $request)
    {
        $query = Activity::with(['category', 'countries'])->active();

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->whereHas('countries', function ($q) use ($request) {
                $q->where('countries.id', $request->country);
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

        $activities = $query->ordered()->paginate(12);
        $categories = ActivityCategory::active()->ordered()->get();
        $countries = Country::active()->ordered()->get();
        $popularActivities = Activity::active()->popular()->ordered()->limit(6)->get();

        return view('activities.index', compact('activities', 'categories', 'countries', 'popularActivities'));
    }

    /**
     * PUBLIC: Show single activity
     */
    public function show($slug)
    {
        $activity = Activity::where('slug', $slug)
                           ->with(['category', 'countries'])
                           ->active()
                           ->firstOrFail();

        // Get related activities from same category
        $relatedActivities = Activity::where('category_id', $activity->category_id)
                                    ->where('id', '!=', $activity->id)
                                    ->active()
                                    ->ordered()
                                    ->limit(3)
                                    ->get();

        return view('activities.show', compact('activity', 'relatedActivities'));
    }

    /**
     * API: Get activities by country
     */
    public function getByCountry($countryId)
    {
        $activities = Activity::whereHas('countries', function ($q) use ($countryId) {
                                  $q->where('countries.id', $countryId);
                              })
                              ->active()
                              ->ordered()
                              ->get();

        return response()->json($activities);
    }

    /**
     * API: Get popular activities
     */
    public function getPopular()
    {
        $activities = Activity::active()
                             ->popular()
                             ->ordered()
                             ->limit(6)
                             ->get();

        return response()->json($activities);
    }

    /**
     * API: Get activities by category
     */
    public function getByCategory($categoryId)
    {
        $activities = Activity::where('category_id', $categoryId)
                             ->active()
                             ->ordered()
                             ->get();

        return response()->json($activities);
    }
}