<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityImage;
use App\Models\Destination;
use App\Models\Country;
use App\Models\ActivityOption;
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
        $query = Activity::with(['category', 'destination.country', 'images', 'countries'])->where('is_active', true);

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

        // Filter by country (via destination's country_id OR countries pivot table)
        if ($request->filled('country')) {
            $query->where(function($q) use ($request) {
                // Match via primary destination's country
                $q->whereHas('destination', function($dq) use ($request) {
                    $dq->where('country_id', $request->country);
                })
                // OR match via many-to-many countries pivot
                ->orWhereHas('countries', function($cq) use ($request) {
                    $cq->where('countries.id', $request->country);
                });
            });
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

        // Get all active categories, destinations, and countries for filters
        $categories   = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();
        $countries    = Country::where('is_active', true)->orderBy('name')->get();

        // Get ALL active activities for hero carousel (no conditions, no limits)
        $featuredActivities = Activity::where('is_active', true)
            ->with(['category', 'destination.country', 'images'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('activities.index', compact('activities', 'categories', 'destinations', 'countries', 'featuredActivities'));
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
            'destinations',
            'options',
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    // Controller-only compatibility layer (no Blade changes needed)
    $includedFromOptions = $activity->includedOptions()->pluck('activity_options.name')->toArray();
    $excludedFromOptions = $activity->excludedOptions()->pluck('activity_options.name')->toArray();
    $bringFromOptions    = $activity->bringOptions()->pluck('activity_options.name')->toArray();

    if (!empty($includedFromOptions)) {
        $activity->inclusions = $includedFromOptions;
    } elseif (!is_array($activity->inclusions)) {
        $activity->inclusions = [];
    }

    if (!empty($excludedFromOptions)) {
        $activity->exclusions = $excludedFromOptions;
    } elseif (!is_array($activity->exclusions)) {
        $activity->exclusions = [];
    }

    if (!empty($bringFromOptions)) {
        $activity->what_to_bring = $bringFromOptions;
    } elseif (!is_array($activity->what_to_bring)) {
        $activity->what_to_bring = [];
    }

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

        // Reusable options
        $bringOptions    = ActivityOption::where('type', 'bring')->where('is_active', true)->orderBy('name')->get();
        $includedOptions = ActivityOption::where('type', 'included')->where('is_active', true)->orderBy('name')->get();
        $excludedOptions = ActivityOption::where('type', 'excluded')->where('is_active', true)->orderBy('name')->get();

        return view('admin.activities.create', compact(
            'categories',
            'destinations',
            'countries',
            'bringOptions',
            'includedOptions',
            'excludedOptions'
        ));
    }

    /**
     * ADMIN: Store a newly created activity
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'category_id'           => 'nullable|exists:activity_categories,id',
            'destination_id'        => 'nullable|exists:destinations,id',
            'name'                  => 'required|string|max:255',
            'slug'                  => 'nullable|string|max:255|unique:activities,slug',
            'description'           => 'nullable|string',
            'overview'              => 'nullable|string',
            'what_to_expect'        => 'nullable|string',
            'highlights'            => 'nullable|string',
            'regulations'           => 'nullable|string',
            'safety_info'           => 'nullable|string',
            'health_requirements'   => 'nullable|string',
            'cultural_experience'   => 'nullable|string',
            'conservation_info'     => 'nullable|string',
            'special_notes'         => 'nullable|string',
            'duration'              => 'nullable|string|max:100',
            'difficulty_level'      => 'nullable|in:easy,moderate,challenging,extreme',
            'min_age'               => 'nullable|integer|min:0|max:100',
            'max_group_size'        => 'nullable|integer|min:1',
            'price_from'            => 'nullable|numeric|min:0',
            'price_to'              => 'nullable|numeric|min:0',
            'currency'              => 'nullable|string|size:3',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string',
            'meta_keywords'         => 'nullable|string',
            'icon'                  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_popular'            => 'boolean',
            'is_active'             => 'boolean',
            'sort_order'            => 'nullable|integer|min:0',
            'countries'             => 'nullable|array',
            'countries.*'           => 'exists:countries,id',
            'destinations'          => 'nullable|array',
            'destinations.*'        => 'integer|exists:destinations,id',
            // JSON fields
            'inclusions'            => 'nullable|array',
            'exclusions'            => 'nullable|array',
            'equipment_provided'    => 'nullable|array',
            'skill_levels'          => 'nullable|array',
            'best_times'            => 'nullable|array',
            'what_to_bring'         => 'nullable|array',
            'pricing_packages'      => 'nullable|array',
            'faqs'                  => 'nullable|array',
            'booking_info'          => 'nullable|array',

            // Reusable selected options
            'bring_option_ids'      => 'nullable|array',
            'bring_option_ids.*'    => 'integer|exists:activity_options,id',
            'included_option_ids'   => 'nullable|array',
            'included_option_ids.*' => 'integer|exists:activity_options,id',
            'excluded_option_ids'   => 'nullable|array',
            'excluded_option_ids.*' => 'integer|exists:activity_options,id',
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

        // Remove non-activities table fields before create
        unset(
            $validated['bring_option_ids'],
            $validated['included_option_ids'],
            $validated['excluded_option_ids']
        );

        // Create activity
        $activity = Activity::create($validated);

        // Sync many-to-many destinations
        $destinationIds = $request->input('destinations', []);
        $activity->destinations()->sync($destinationIds);

        // Sync countries relationship
        if ($request->has('countries')) {
            $activity->countries()->sync($request->countries);
        }

        // Sync reusable options
        $optionIds = array_merge(
            $request->input('bring_option_ids', []),
            $request->input('included_option_ids', []),
            $request->input('excluded_option_ids', [])
        );
        $activity->options()->sync(array_values(array_unique($optionIds)));

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('activities/gallery', 'public');

                $activity->images()->create([
                    'image_path'  => $path,
                    'sort_order'  => $index + 1,
                    'is_featured' => $index === 0
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

        $selectedCountries    = $activity->countries()->pluck('countries.id')->toArray();
        $selectedDestinations = $activity->destinations()->pluck('destinations.id')->toArray();

        // Reusable options
        $bringOptions    = ActivityOption::where('type', 'bring')->where('is_active', true)->orderBy('name')->get();
        $includedOptions = ActivityOption::where('type', 'included')->where('is_active', true)->orderBy('name')->get();
        $excludedOptions = ActivityOption::where('type', 'excluded')->where('is_active', true)->orderBy('name')->get();

        $selectedBringOptionIds    = $activity->bringOptions()->pluck('activity_options.id')->toArray();
        $selectedIncludedOptionIds = $activity->includedOptions()->pluck('activity_options.id')->toArray();
        $selectedExcludedOptionIds = $activity->excludedOptions()->pluck('activity_options.id')->toArray();

        return view(
            'admin.activities.edit',
            compact(
                'activity',
                'categories',
                'destinations',
                'countries',
                'selectedCountries',
                'selectedDestinations',
                'bringOptions',
                'includedOptions',
                'excludedOptions',
                'selectedBringOptionIds',
                'selectedIncludedOptionIds',
                'selectedExcludedOptionIds'
            )
        );
    }

    /**
     * ADMIN: Update the specified activity
     */
    public function adminUpdate(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'category_id'           => 'nullable|exists:activity_categories,id',
            'destination_id'        => 'nullable|exists:destinations,id',
            'name'                  => 'required|string|max:255',
            'slug'                  => 'nullable|string|max:255|unique:activities,slug,' . $activity->id,
            'description'           => 'nullable|string',
            'overview'              => 'nullable|string',
            'what_to_expect'        => 'nullable|string',
            'highlights'            => 'nullable|string',
            'regulations'           => 'nullable|string',
            'safety_info'           => 'nullable|string',
            'health_requirements'   => 'nullable|string',
            'cultural_experience'   => 'nullable|string',
            'conservation_info'     => 'nullable|string',
            'special_notes'         => 'nullable|string',
            'duration'              => 'nullable|string|max:100',
            'difficulty_level'      => 'nullable|in:easy,moderate,challenging,extreme',
            'min_age'               => 'nullable|integer|min:0|max:100',
            'max_group_size'        => 'nullable|integer|min:1',
            'price_from'            => 'nullable|numeric|min:0',
            'price_to'              => 'nullable|numeric|min:0',
            'currency'              => 'nullable|string|size:3',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string',
            'meta_keywords'         => 'nullable|string',
            'icon'                  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_popular'            => 'boolean',
            'is_active'             => 'boolean',
            'sort_order'            => 'nullable|integer|min:0',
            'countries'             => 'nullable|array',
            'countries.*'           => 'exists:countries,id',
            'destinations'          => 'nullable|array',
            'destinations.*'        => 'integer|exists:destinations,id',
            // JSON fields
            'inclusions'            => 'nullable|array',
            'exclusions'            => 'nullable|array',
            'equipment_provided'    => 'nullable|array',
            'skill_levels'          => 'nullable|array',
            'best_times'            => 'nullable|array',
            'what_to_bring'         => 'nullable|array',
            'pricing_packages'      => 'nullable|array',
            'faqs'                  => 'nullable|array',
            'booking_info'          => 'nullable|array',

            // Reusable selected options
            'bring_option_ids'      => 'nullable|array',
            'bring_option_ids.*'    => 'integer|exists:activity_options,id',
            'included_option_ids'   => 'nullable|array',
            'included_option_ids.*' => 'integer|exists:activity_options,id',
            'excluded_option_ids'   => 'nullable|array',
            'excluded_option_ids.*' => 'integer|exists:activity_options,id',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            if ($activity->icon) Storage::disk('public')->delete($activity->icon);
            $validated['icon'] = $request->file('icon')->store('activities/icons', 'public');
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            if ($activity->image) Storage::disk('public')->delete($activity->image);
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($activity->featured_image) Storage::disk('public')->delete($activity->featured_image);
            $validated['featured_image'] = $request->file('featured_image')->store('activities/featured', 'public');
        }

        // Set boolean fields
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active']  = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $activity->sort_order;

        // Remove non-activities table fields before update
        unset(
            $validated['bring_option_ids'],
            $validated['included_option_ids'],
            $validated['excluded_option_ids']
        );

        // Update activity
        $activity->update($validated);

        // Sync many-to-many destinations
        $destinationIds = $request->input('destinations', []);
        $activity->destinations()->sync($destinationIds);

        // Sync countries relationship
        if ($request->has('countries')) {
            $activity->countries()->sync($request->countries);
        } else {
            $activity->countries()->detach();
        }

        // Sync reusable options
        $optionIds = array_merge(
            $request->input('bring_option_ids', []),
            $request->input('included_option_ids', []),
            $request->input('excluded_option_ids', [])
        );
        $activity->options()->sync(array_values(array_unique($optionIds)));

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
        if ($activity->icon) Storage::disk('public')->delete($activity->icon);
        if ($activity->image) Storage::disk('public')->delete($activity->image);
        if ($activity->featured_image) Storage::disk('public')->delete($activity->featured_image);

        foreach ($activity->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

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

    /**
 * ADMIN: Reusable activity options page
 */
public function adminOptionsIndex(Request $request)
{
    $query = ActivityOption::query()->orderBy('type')->orderBy('name');

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $options = $query->paginate(30)->withQueryString();

    return view('admin.activities.activity-options', compact('options'));
}

/**
 * ADMIN: Store reusable activity option
 */
public function adminOptionsStore(Request $request)
{
    $validated = $request->validate([
        'type'      => 'required|in:bring,included,excluded',
        'name'      => 'required|string|max:255',
        'is_active' => 'nullable|boolean',
    ]);

    $name = trim($validated['name']);

    // Prevent duplicates per type (case-insensitive)
    $exists = ActivityOption::where('type', $validated['type'])
        ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['name' => 'This option already exists for the selected type.'])
            ->withInput();
    }

    ActivityOption::create([
        'type'      => $validated['type'],
        'name'      => $name,
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()->route('admin.activities.options.index')
        ->with('success', 'Activity option created successfully.');
}

/**
 * ADMIN: Update reusable activity option
 */
public function adminOptionsUpdate(Request $request, ActivityOption $option)
{
    $validated = $request->validate([
        'type'      => 'required|in:bring,included,excluded',
        'name'      => 'required|string|max:255',
        'is_active' => 'nullable|boolean',
    ]);

    $name = trim($validated['name']);

    // Prevent duplicates per type (case-insensitive), excluding current row
    $exists = ActivityOption::where('type', $validated['type'])
        ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
        ->where('id', '!=', $option->id)
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['name' => 'Another option with the same text already exists for this type.'])
            ->withInput();
    }

    $option->update([
        'type'      => $validated['type'],
        'name'      => $name,
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()->route('admin.activities.options.index')
        ->with('success', 'Activity option updated successfully.');
}

/**
 * ADMIN: Delete reusable activity option
 */
public function adminOptionsDestroy(ActivityOption $option)
{
    // Detach from activities first (safe cleanup)
    $option->activities()->detach();
    $option->delete();

    return redirect()->route('admin.activities.options.index')
        ->with('success', 'Activity option deleted successfully.');
}
}