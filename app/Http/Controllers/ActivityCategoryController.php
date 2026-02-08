<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityCategoryController extends Controller
{
    /**
     * ADMIN: Display a listing of activity categories
     */
    public function adminIndex(Request $request)
    {
        $query = ActivityCategory::withCount('activities');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Fixed variable name from 'categories' to 'activityCategories'
        $activityCategories = $query->orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('admin.activity-categories.index', compact('activityCategories'));
    }

    /**
     * ADMIN: Show the form for creating a new category
     */
    public function adminCreate()
    {
        return view('admin.activity-categories.create');
    }

    /**
     * ADMIN: Store a newly created category
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activity_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ActivityCategory::create($validated);

        return redirect()->route('admin.activity-categories.index')
                        ->with('success', 'Activity category created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified category
     */
    public function adminEdit(ActivityCategory $activityCategory)
    {
        return view('admin.activity-categories.edit', compact('activityCategory'));
    }

    /**
     * ADMIN: Update the specified category
     */
    public function adminUpdate(Request $request, ActivityCategory $activityCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activity_categories,slug,' . $activityCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $activityCategory->sort_order;

        $activityCategory->update($validated);

        return redirect()->route('admin.activity-categories.index')
                        ->with('success', 'Activity category updated successfully!');
    }

    /**
     * ADMIN: Remove the specified category
     */
    public function adminDestroy(ActivityCategory $activityCategory)
    {
        // Check if category has activities
        if ($activityCategory->activities()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing activities!');
        }

        $activityCategory->delete();

        return redirect()->route('admin.activity-categories.index')
                        ->with('success', 'Activity category deleted successfully!');
    }

    /**
     * ADMIN: Toggle category active status
     */
    public function adminToggleStatus(ActivityCategory $activityCategory)
    {
        $activityCategory->update([
            'is_active' => !$activityCategory->is_active
        ]);

        $status = $activityCategory->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Category {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete categories
     */
    public function adminBulkDelete(Request $request)
    {
        $ids = json_decode($request->ids);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No categories selected!');
        }

        $categories = ActivityCategory::whereIn('id', $ids)->get();
        $deleted = 0;

        foreach ($categories as $category) {
            // Only delete if no activities exist
            if ($category->activities()->count() === 0) {
                $category->delete();
                $deleted++;
            }
        }

        if ($deleted === 0) {
            return redirect()->back()->with('error', 'Cannot delete categories with existing activities!');
        }

        return redirect()->back()
                        ->with('success', "{$deleted} category(ies) deleted successfully!");
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $order = $request->order;

        if (empty($order)) {
            return response()->json(['success' => false, 'message' => 'No order data provided']);
        }

        foreach ($order as $index => $id) {
            ActivityCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of activity categories (for website)
     */
    public function index()
    {
        $categories = ActivityCategory::where('is_active', true)
                                     ->withCount(['activities' => function ($query) {
                                         $query->where('is_active', true);
                                     }])
                                     ->orderBy('sort_order')
                                     ->orderBy('name')
                                     ->get();

        return view('activity-categories.index', compact('categories'));
    }

    /**
     * PUBLIC: Show single category with activities
     */
    public function show(ActivityCategory $activityCategory)
    {
        if (!$activityCategory->is_active) {
            abort(404);
        }

        $activities = $activityCategory->activities()
                                      ->where('is_active', true)
                                      ->orderBy('sort_order')
                                      ->orderBy('name')
                                      ->paginate(12);

        return view('activity-categories.show', compact('activityCategory', 'activities'));
    }

    /**
     * API: Get all active categories
     */
    public function getCategories()
    {
        $categories = ActivityCategory::where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->orderBy('name')
                                     ->get();
        
        return response()->json($categories);
    }

    /**
     * API: Get activities by category
     */
    public function getActivitiesByCategory($categoryId)
    {
        $category = ActivityCategory::findOrFail($categoryId);
        
        $activities = $category->activities()
                              ->where('is_active', true)
                              ->orderBy('sort_order')
                              ->orderBy('name')
                              ->get();

        return response()->json($activities);
    }
}