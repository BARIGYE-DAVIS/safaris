<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Destination;
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
        $query = Activity::with(['category', 'destination.country']);

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

        // Filter by destination
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by popular
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'yes');
        }

        $activities = $query->orderBy('sort_order')->orderBy('name')->paginate(15);
        
        // Get categories and destinations for filters
        $categories = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        return view('admin.activities.index', compact('activities', 'categories', 'destinations'));
    }

    /**
     * ADMIN: Show the form for creating a new activity
     */
    public function adminCreate()
    {
        $categories = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();
        
        return view('admin.activities.create', compact('categories', 'destinations'));
    }

    /**
     * ADMIN: Store a newly created activity
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:activity_categories,id',
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activities,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('activities', 'public');
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Activity::create($validated);

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified activity
     */
    public function adminEdit(Activity $activity)
    {
        $categories = ActivityCategory::where('is_active', true)->orderBy('name')->get();
        $destinations = Destination::where('is_active', true)->with('country')->orderBy('name')->get();
        
        return view('admin.activities.edit', compact('activity', 'categories', 'destinations'));
    }

    /**
     * ADMIN: Update the specified activity
     */
    public function adminUpdate(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:activity_categories,id',
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:activities,slug,' . $activity->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
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

        $activity->update($validated);

        return redirect()->route('admin.activities.index')
                        ->with('success', 'Activity updated successfully!');
    }

    /**
     * ADMIN: Remove the specified activity
     */
    public function adminDestroy(Activity $activity)
    {
        // Delete image if exists
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

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
        $ids = json_decode($request->ids);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No activities selected!');
        }

        $activities = Activity::whereIn('id', $ids)->get();

        foreach ($activities as $activity) {
            // Delete image if exists
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $activity->delete();
        }

        return redirect()->back()
                        ->with('success', count($ids) . ' activity(ies) deleted successfully!');
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
            Activity::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }
}