<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityImage;
use App\Models\Destination;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    // ============================================
    // PUBLIC METHODS (Frontend)
    // ============================================

    /**
     * PUBLIC: Display list of activities
     */
    public function index(Request $request)
    {
        $query = Activity::with(['category', 'destination.country', 'images'])->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('overview', 'like', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by primary destination (legacy single destination_id)
        if ($request->filled('destination')) {
            $query->where('destination_id', $request->destination);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Filter by price range
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price_from', [$request->price_min, $request->price_max]);
        }

        $activities = $query->orderBy('sort_order')->orderBy('name')->paginate(12);
        
        // Get all active categories and destinations for filters
        $categories = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();

        // Get ALL active activities for hero carousel (no conditions, no limits)
        $featuredActivities = Activity::where('is_active', true)
            ->with(['category', 'destination.country', 'images'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('activities.index', compact('activities', 'categories', 'destinations', 'featuredActivities'));
    }

    /**
     * PUBLIC: Display single activity details
     */
    public function show($slug)
    {
        $activity = Activity::with([
                'category', 
                'destination.country', 
                'countries', 
                'images' => function($query) {
                    $query->orderBy('sort_order');
                },
                // >>> ADDED: eager load many-to-many destinations for display if needed
                'destinations',
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related activities (same category)
        $relatedActivities = Activity::where('category_id', $activity->category_id)
                                    ->where('id', '!=', $activity->id)
                                    ->where('is_active', true)
                                    ->orderBy('is_popular', 'desc')
                                    ->limit(4)
                                    ->get();

        return view('activities.show', compact('activity', 'relatedActivities'));
    }

    // ============================================
    // ADMIN METHODS (Dashboard)
    // ============================================

    /**
     * ADMIN: Display list of all activities
     */
    public function adminIndex(Request $request)
    {
        $query = Activity::with(['category', 'destination.country', 'images'])
                         ->orderBy('sort_order')
                         ->orderBy('name');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('slug', 'like', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by primary destination (legacy destination_id)
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * ADMIN: Show the form for creating a new activity
     */
    public function adminCreate()
    {
        $categories   = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();
        $countries    = Country::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.activities.create', compact('categories', 'destinations', 'countries'));
    }

    /**
     * ADMIN: Store a newly created activity
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'category_id'     => 'nullable|exists:activity_categories,id',
            'destination_id'  => 'nullable|exists:destinations,id',
            'name'            => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255|unique:activities,slug',
            'description'     => 'nullable|string',
            'overview'        => 'nullable|string',
            'what_to_expect'  => 'nullable|string',
            'highlights'      => 'nullable|string',
            'regulations'     => 'nullable|string',
            'safety_info'     => 'nullable|string',
            'health_requirements'   => 'nullable|string',
            'cultural_experience'   => 'nullable|string',
            'conservation_info'     => 'nullable|string',
            'special_notes'         => 'nullable|string',
            'duration'              => 'nullable|string|max:100',
            'difficulty_level'      => 'nullable|in:easy,moderate,challenging,extreme',
            'min_age'              => 'nullable|integer|min:0|max:100',
            'max_group_size'       => 'nullable|integer|min:1',
            'price_from'           => 'nullable|numeric|min:0',
            'price_to'             => 'nullable|numeric|min:0',
            'currency'             => 'nullable|string|size:3',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string',
            'meta_keywords'        => 'nullable|string',
            'icon'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_popular'           => 'boolean',
            'is_active'            => 'boolean',
            'sort_order'           => 'nullable|integer|min:0',
            'countries'            => 'nullable|array',
            'countries.*'          => 'exists:countries,id',

            // >>> ADDED: multi-destination checkboxes
            'destinations'   => 'nullable|array',
            'destinations.*' => 'integer|exists:destinations,id',
            
            // JSON fields
            'inclusions'         => 'nullable|array',
            'exclusions'         => 'nullable|array',
            'equipment_provided' => 'nullable|array',
            'skill_levels'       => 'nullable|array',
            'best_times'         => 'nullable|array',
            'what_to_bring'      => 'nullable|array',
            'pricing_packages'   => 'nullable|array',
            'faqs'               => 'nullable|array',
            'booking_info'       => 'nullable|array',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('activities/icons', 'public');
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('activities/featured', 'public');
        }

        // Set boolean fields
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active']  = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['currency']   = $validated['currency'] ?? 'USD';

        // Create activity
        $activity = Activity::create($validated);

        // >>> ADDED: sync many-to-many destinations from checkboxes
        $destinationIds = $request->input('destinations', []);
        $activity->destinations()->sync($destinationIds);

        // Sync countries relationship
        if ($request->has('countries')) {
            $activity->countries()->sync($request->countries);
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('activities/gallery', 'public');
                
                $activity->images()->create([
                    'image_path'  => $path,
                    'sort_order'  => $index + 1,
                    'is_featured' => $index === 0 // First image is featured by default
                ]);
            }
        }

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified activity
     */
    public function adminEdit(Activity $activity)
    {
        $categories   = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();
        $countries    = Country::where('is_active', true)->orderBy('name')->get();
        
        // Get currently selected countries for this activity
        $selectedCountries = $activity->countries()->pluck('countries.id')->toArray();

        // >>> ADDED: currently selected destinations for this activity (from pivot)
        $selectedDestinations = $activity->destinations()->pluck('destinations.id')->toArray();
        
        return view(
            'admin.activities.edit',
            compact('activity', 'categories', 'destinations', 'countries', 'selectedCountries', 'selectedDestinations')
        );
    }

    /**
     * ADMIN: Update the specified activity
     */
    public function adminUpdate(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'category_id'     => 'nullable|exists:activity_categories,id',
            'destination_id'  => 'nullable|exists:destinations,id',
            'name'            => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255|unique:activities,slug,' . $activity->id,
            'description'     => 'nullable|string',
            'overview'        => 'nullable|string',
            'what_to_expect'  => 'nullable|string',
            'highlights'      => 'nullable|string',
            'regulations'     => 'nullable|string',
            'safety_info'     => 'nullable|string',
            'health_requirements'   => 'nullable|string',
            'cultural_experience'   => 'nullable|string',
            'conservation_info'     => 'nullable|string',
            'special_notes'         => 'nullable|string',
            'duration'              => 'nullable|string|max:100',
            'difficulty_level'      => 'nullable|in:easy,moderate,challenging,extreme',
            'min_age'              => 'nullable|integer|min:0|max:100',
            'max_group_size'       => 'nullable|integer|min:1',
            'price_from'           => 'nullable|numeric|min:0',
            'price_to'             => 'nullable|numeric|min:0',
            'currency'             => 'nullable|string|size:3',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'     => 'nullable|string',
            'meta_keywords'        => 'nullable|string',
            'icon'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_popular'           => 'boolean',
            'is_active'            => 'boolean',
            'sort_order'           => 'nullable|integer|min:0',
            'countries'            => 'nullable|array',
            'countries.*'          => 'exists:countries,id',

            // >>> ADDED: multi-destination checkboxes
            'destinations'   => 'nullable|array',
            'destinations.*' => 'integer|exists:destinations,id',
            
            // JSON fields
            'inclusions'         => 'nullable|array',
            'exclusions'         => 'nullable|array',
            'equipment_provided' => 'nullable|array',
            'skill_levels'       => 'nullable|array',
            'best_times'         => 'nullable|array',
            'what_to_bring'      => 'nullable|array',
            'pricing_packages'   => 'nullable|array',
            'faqs'               => 'nullable|array',
            'booking_info'       => 'nullable|array',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            if ($activity->icon) {
                Storage::disk('public')->delete($activity->icon);
            }
            $validated['icon'] = $request->file('icon')->store('activities/icons', 'public');
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($activity->featured_image) {
                Storage::disk('public')->delete($activity->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('activities/featured', 'public');
        }

        // Set boolean fields
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active']  = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $activity->sort_order;

        // Update activity
        $activity->update($validated);
        
        // >>> ADDED: sync many-to-many destinations from checkboxes
        $destinationIds = $request->input('destinations', []);
        $activity->destinations()->sync($destinationIds);

        // Sync countries relationship
        if ($request->has('countries')) {
            $activity->countries()->sync($request->countries);
        } else {
            $activity->countries()->detach();
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $currentMaxSort = $activity->images()->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('activities/gallery', 'public');
                
                $activity->images()->create([
                    'image_path'  => $path,
                    'sort_order'  => $currentMaxSort + $index + 1,
                    'is_featured' => false
                ]);
            }
        }

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->ids);
        
        if (empty($ids)) {
            return back()->with('error', 'No activities selected');
        }

        $activities = Activity::whereIn('id', $ids)->get();
        
        foreach ($activities as $activity) {
            // Delete images
            if ($activity->icon) Storage::disk('public')->delete($activity->icon);
            if ($activity->image) Storage::disk('public')->delete($activity->image);
            if ($activity->featured_image) Storage::disk('public')->delete($activity->featured_image);
            
            foreach ($activity->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
            
            $activity->delete();
        }

        return redirect()->route('admin.activities.index')
                        ->with('success', count($ids) . ' activities deleted successfully!');
    }

    /**
     * ADMIN: Remove the specified activity
     */
    public function adminDestroy(Activity $activity)
    {
        // Delete associated images from storage
        if ($activity->icon) {
            Storage::disk('public')->delete($activity->icon);
        }
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }
        if ($activity->featured_image) {
            Storage::disk('public')->delete($activity->featured_image);
        }

        // Delete gallery images
        foreach ($activity->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete activity
        $activity->delete();

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity deleted successfully!');
    }

    /**
     * ADMIN: Toggle activity active status
     */
    public function adminToggleActive(Activity $activity)
    {
        $activity->update(['is_active' => !$activity->is_active]);
        
        $status = $activity->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Activity {$status} successfully!");
    }

    /**
     * ADMIN: Toggle activity popular status
     */
    public function adminTogglePopular(Activity $activity)
    {
        $activity->update(['is_popular' => !$activity->is_popular]);
        
        $status = $activity->is_popular ? 'marked as popular' : 'unmarked as popular';
        return back()->with('success', "Activity {$status} successfully!");
    } 
}