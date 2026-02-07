<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BudgetCategoryController extends Controller
{
    /**
     * ADMIN: Display a listing of budget categories
     */
    public function adminIndex(Request $request)
    {
        $query = BudgetCategory::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $budgetCategories = $query->ordered()->paginate(15);

        return view('admin.budget-categories.index', compact('budgetCategories'));
    }

    /**
     * ADMIN: Show the form for creating a new budget category
     */
    public function adminCreate()
    {
        return view('admin.budget-categories.create');
    }

    /**
     * ADMIN: Store a newly created budget category
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:budget_categories,slug',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0|gte:price_range_min',
            'currency' => 'nullable|string|max:3',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['currency'] = $validated['currency'] ?? 'USD';

        // Convert features to JSON if provided
        if ($request->filled('features')) {
            $validated['features'] = array_values(array_filter($request->features));
        }

        BudgetCategory::create($validated);

        return redirect()->route('admin.budget-categories.index')
                        ->with('success', 'Budget category created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified budget category
     */
    public function adminEdit(BudgetCategory $budgetCategory)
    {
        return view('admin.budget-categories.edit', compact('budgetCategory'));
    }

    /**
     * ADMIN: Update the specified budget category
     */
    public function adminUpdate(Request $request, BudgetCategory $budgetCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:budget_categories,slug,' . $budgetCategory->id,
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0|gte:price_range_min',
            'currency' => 'nullable|string|max:3',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $budgetCategory->sort_order;
        $validated['currency'] = $validated['currency'] ?? 'USD';

        // Convert features to JSON if provided
        if ($request->filled('features')) {
            $validated['features'] = array_values(array_filter($request->features));
        } else {
            $validated['features'] = [];
        }

        $budgetCategory->update($validated);

        return redirect()->route('admin.budget-categories.index')
                        ->with('success', 'Budget category updated successfully!');
    }

    /**
     * ADMIN: Remove the specified budget category
     */
    public function adminDestroy(BudgetCategory $budgetCategory)
    {
        $budgetCategory->delete();

        return redirect()->route('admin.budget-categories.index')
                        ->with('success', 'Budget category deleted successfully!');
    }

    /**
     * ADMIN: Toggle budget category active status
     */
    public function adminToggleStatus(BudgetCategory $budgetCategory)
    {
        $budgetCategory->update([
            'is_active' => !$budgetCategory->is_active
        ]);

        $status = $budgetCategory->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Budget category {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete budget categories
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:budget_categories,id'
        ]);

        BudgetCategory::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected budget categories deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:budget_categories,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            BudgetCategory::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of budget categories
     */
    public function index()
    {
        $budgetCategories = BudgetCategory::active()->ordered()->get();

        return view('budget-categories.index', compact('budgetCategories'));
    }

    /**
     * PUBLIC: Show single budget category
     */
    public function show($slug)
    {
        $budgetCategory = BudgetCategory::where('slug', $slug)
                                       ->active()
                                       ->firstOrFail();

        // Get all budget categories for comparison
        $allCategories = BudgetCategory::active()->ordered()->get();

        return view('budget-categories.show', compact('budgetCategory', 'allCategories'));
    }

    /**
     * API: Get all active budget categories
     */
    public function getCategories()
    {
        $budgetCategories = BudgetCategory::active()->ordered()->get();
        
        return response()->json($budgetCategories);
    }

    /**
     * API: Get budget category by slug
     */
    public function getBySlug($slug)
    {
        $budgetCategory = BudgetCategory::where('slug', $slug)
                                       ->active()
                                       ->firstOrFail();

        return response()->json($budgetCategory);
    }
}