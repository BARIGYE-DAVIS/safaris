<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class ActivityCategoryController extends Controller
{
    /**
     * ADMIN: Display a listing of activity categories
     */
    public function adminIndex(Request $request)
    {
        try {
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

            $activityCategories = $query->orderBy('sort_order')->orderBy('name')->paginate(15);

            return view('admin.activity-categories.index', compact('activityCategories'));
        } catch (Exception $e) {
            Log::error('Error in adminIndex: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error loading categories: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Show the form for creating a new category
     */
    public function adminCreate()
    {
        try {
            return view('admin.activity-categories.create');
        } catch (Exception $e) {
            Log::error('Error in adminCreate: ' . $e->getMessage());
            return back()->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Store a newly created category
     */
    public function adminStore(Request $request)
    {
        try {
            Log::info('Creating activity category', ['request_data' => $request->all()]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:activity_categories,slug',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);

            // Auto-generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('activity-categories', 'public');
                Log::info('Image uploaded', ['image_path' => $validated['image']]);
            }

            // Set boolean and integer values properly
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            Log::info('Data before create', ['final_data' => $validated]);

            $category = ActivityCategory::create($validated);

            Log::info('Category created successfully', [
                'category_id' => $category->id,
                'category_data' => $category->toArray()
            ]);

            return redirect()->route('admin.activity-categories.index')
                            ->with('success', 'Activity category created successfully!');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in adminStore', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (Exception $e) {
            Log::error('Error creating activity category', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Error creating category: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ADMIN: Show the form for editing the specified category
     */
    public function adminEdit(ActivityCategory $activityCategory)
    {
        try {
            Log::info('Editing category', ['category_id' => $activityCategory->id]);
            
            $activityCategory->load('activities');
            
            return view('admin.activity-categories.edit', compact('activityCategory'));
        } catch (Exception $e) {
            Log::error('Error in adminEdit', [
                'category_id' => $activityCategory->id ?? null,
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Update the specified category
     */
    public function adminUpdate(Request $request, ActivityCategory $activityCategory)
    {
        try {
            Log::info('Updating activity category', [
                'category_id' => $activityCategory->id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:activity_categories,slug,' . $activityCategory->id,
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);

            // Auto-generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($activityCategory->image && Storage::disk('public')->exists($activityCategory->image)) {
                    Storage::disk('public')->delete($activityCategory->image);
                    Log::info('Old image deleted', ['old_image' => $activityCategory->image]);
                }
                
                $validated['image'] = $request->file('image')->store('activity-categories', 'public');
                Log::info('New image uploaded', ['image_path' => $validated['image']]);
            }

            // Set boolean value properly
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? $activityCategory->sort_order;

            Log::info('Data before update', ['final_data' => $validated]);

            $activityCategory->update($validated);

            Log::info('Category updated successfully', [
                'category_id' => $activityCategory->id,
                'category_data' => $activityCategory->fresh()->toArray()
            ]);

            return redirect()->route('admin.activity-categories.index')
                            ->with('success', 'Activity category updated successfully!');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in adminUpdate', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (Exception $e) {
            Log::error('Error updating activity category', [
                'category_id' => $activityCategory->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Error updating category: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ADMIN: Remove the specified category
     */
    public function adminDestroy(ActivityCategory $activityCategory)
    {
        try {
            Log::info('Deleting category', ['category_id' => $activityCategory->id]);

            // Check if category has activities
            if ($activityCategory->activities()->count() > 0) {
                Log::warning('Cannot delete category with activities', [
                    'category_id' => $activityCategory->id,
                    'activities_count' => $activityCategory->activities()->count()
                ]);
                return back()->with('error', 'Cannot delete category with existing activities!');
            }

            // Delete image if exists
            if ($activityCategory->image && Storage::disk('public')->exists($activityCategory->image)) {
                Storage::disk('public')->delete($activityCategory->image);
                Log::info('Image deleted', ['image_path' => $activityCategory->image]);
            }

            $activityCategory->delete();

            Log::info('Category deleted successfully', ['category_id' => $activityCategory->id]);

            return redirect()->route('admin.activity-categories.index')
                            ->with('success', 'Activity category deleted successfully!');
                            
        } catch (Exception $e) {
            Log::error('Error deleting category', [
                'category_id' => $activityCategory->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Toggle category active status
     */
    public function adminToggleStatus(ActivityCategory $activityCategory)
    {
        try {
            Log::info('Toggling category status', [
                'category_id' => $activityCategory->id,
                'current_status' => $activityCategory->is_active
            ]);

            $activityCategory->update([
                'is_active' => !$activityCategory->is_active
            ]);

            $status = $activityCategory->is_active ? 'activated' : 'deactivated';
            
            Log::info('Category status toggled', [
                'category_id' => $activityCategory->id,
                'new_status' => $activityCategory->is_active
            ]);

            return back()->with('success', "Category {$status} successfully!");
            
        } catch (Exception $e) {
            Log::error('Error toggling category status', [
                'category_id' => $activityCategory->id,
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Error toggling status: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Bulk delete categories
     */
    public function adminBulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->ids);

            Log::info('Bulk deleting categories', ['ids' => $ids]);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'No categories selected!');
            }

            $categories = ActivityCategory::whereIn('id', $ids)->get();
            $deleted = 0;

            foreach ($categories as $category) {
                // Only delete if no activities exist
                if ($category->activities()->count() === 0) {
                    // Delete image if exists
                    if ($category->image && Storage::disk('public')->exists($category->image)) {
                        Storage::disk('public')->delete($category->image);
                    }
                    $category->delete();
                    $deleted++;
                }
            }

            Log::info('Bulk delete completed', [
                'requested' => count($ids),
                'deleted' => $deleted
            ]);

            if ($deleted === 0) {
                return redirect()->back()->with('error', 'Cannot delete categories with existing activities!');
            }

            return redirect()->back()
                            ->with('success', "{$deleted} category(ies) deleted successfully!");
                            
        } catch (Exception $e) {
            Log::error('Error in bulk delete', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting categories: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        try {
            $order = $request->order;

            Log::info('Updating category order', ['order' => $order]);

            if (empty($order)) {
                return response()->json(['success' => false, 'message' => 'No order data provided']);
            }

            foreach ($order as $index => $id) {
                ActivityCategory::where('id', $id)->update(['sort_order' => $index]);
            }

            Log::info('Category order updated successfully');

            return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
            
        } catch (Exception $e) {
            Log::error('Error updating category order', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()]);
        }
    }

    /**
     * PUBLIC: Display list of activity categories (for website)
     */
    public function index()
    {
        try {
            $categories = ActivityCategory::where('is_active', true)
                                         ->withCount(['activities' => function ($query) {
                                             $query->where('is_active', true);
                                         }])
                                         ->orderBy('sort_order')
                                         ->orderBy('name')
                                         ->get();

            return view('activity-categories.index', compact('categories'));
            
        } catch (Exception $e) {
            Log::error('Error loading public categories', [
                'message' => $e->getMessage()
            ]);
            abort(500, 'Error loading categories');
        }
    }

    /**
     * PUBLIC: Show single category with activities
     */
    public function show(ActivityCategory $activityCategory)
    {
        try {
            if (!$activityCategory->is_active) {
                abort(404);
            }

            $activities = $activityCategory->activities()
                                          ->where('is_active', true)
                                          ->orderBy('sort_order')
                                          ->orderBy('name')
                                          ->paginate(12);

            return view('activity-categories.show', compact('activityCategory', 'activities'));
            
        } catch (Exception $e) {
            Log::error('Error showing category', [
                'category_id' => $activityCategory->id ?? null,
                'message' => $e->getMessage()
            ]);
            abort(500, 'Error loading category');
        }
    }

    /**
     * API: Get all active categories
     */
    public function getCategories()
    {
        try {
            $categories = ActivityCategory::where('is_active', true)
                                         ->orderBy('sort_order')
                                         ->orderBy('name')
                                         ->get();
            
            return response()->json($categories);
            
        } catch (Exception $e) {
            Log::error('Error getting categories API', [
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Error loading categories'], 500);
        }
    }

    /**
     * API: Get activities by category
     */
    public function getActivitiesByCategory($categoryId)
    {
        try {
            $category = ActivityCategory::findOrFail($categoryId);
            
            $activities = $category->activities()
                                  ->where('is_active', true)
                                  ->orderBy('sort_order')
                                  ->orderBy('name')
                                  ->get();

            return response()->json($activities);
            
        } catch (Exception $e) {
            Log::error('Error getting activities by category', [
                'category_id' => $categoryId,
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Error loading activities'], 500);
        }
    }
}