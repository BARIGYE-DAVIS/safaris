<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\Request;

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
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->ordered()->paginate(15);

        return view('admin.activity-categories.index', compact('categories'));
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
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

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
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_categories,id'
        ]);

        ActivityCategory::whereIn('id', $request->ids)
                       ->whereDoesntHave('activities')
                       ->delete();

        return back()->with('success', 'Selected categories deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:activity_categories,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            ActivityCategory::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of activity categories (for website)
     */
    public function index()
    {
        $categories = ActivityCategory::active()
                                     ->withCount(['activities' => function ($query) {
                                         $query->where('is_active', true);
                                     }])
                                     ->ordered()
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
                                      ->active()
                                      ->ordered()
                                      ->paginate(12);

        return view('activity-categories.show', compact('activityCategory', 'activities'));
    }

    /**
     * API: Get all active categories
     */
    public function getCategories()
    {
        $categories = ActivityCategory::active()->ordered()->get();
        
        return response()->json($categories);
    }

    /**
     * API: Get activities by category
     */
    public function getActivitiesByCategory($categoryId)
    {
        $activities = ActivityCategory::findOrFail($categoryId)
                                     ->activities()
                                     ->active()
                                     ->ordered()
                                     ->get();

        return response()->json($activities);
    }
}