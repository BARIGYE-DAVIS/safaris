<?php

namespace App\Http\Controllers;

use App\Models\AccommodationType;
use Illuminate\Http\Request;

class AccommodationTypeController extends Controller
{
    /**
     * ADMIN: Display a listing of accommodation types
     */
    public function adminIndex(Request $request)
    {
        $query = AccommodationType::query();

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

        $accommodationTypes = $query->ordered()->paginate(15);

        return view('admin.accommodation-types.index', compact('accommodationTypes'));
    }

    /**
     * ADMIN: Show the form for creating a new accommodation type
     */
    public function adminCreate()
    {
        return view('admin.accommodation-types.create');
    }

    /**
     * ADMIN: Store a newly created accommodation type
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        AccommodationType::create($validated);

        return redirect()->route('admin.accommodation-types.index')
                        ->with('success', 'Accommodation type created successfully!');
    }

    /**
     * ADMIN: Show the form for editing the specified accommodation type
     */
    public function adminEdit(AccommodationType $accommodationType)
    {
        return view('admin.accommodation-types.edit', compact('accommodationType'));
    }

    /**
     * ADMIN: Update the specified accommodation type
     */
    public function adminUpdate(Request $request, AccommodationType $accommodationType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $accommodationType->sort_order;

        $accommodationType->update($validated);

        return redirect()->route('admin.accommodation-types.index')
                        ->with('success', 'Accommodation type updated successfully!');
    }

    /**
     * ADMIN: Remove the specified accommodation type
     */
    public function adminDestroy(AccommodationType $accommodationType)
    {
        $accommodationType->delete();

        return redirect()->route('admin.accommodation-types.index')
                        ->with('success', 'Accommodation type deleted successfully!');
    }

    /**
     * ADMIN: Toggle accommodation type active status
     */
    public function adminToggleStatus(AccommodationType $accommodationType)
    {
        $accommodationType->update([
            'is_active' => !$accommodationType->is_active
        ]);

        $status = $accommodationType->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Accommodation type {$status} successfully!");
    }

    /**
     * ADMIN: Bulk delete accommodation types
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:accommodation_types,id'
        ]);

        AccommodationType::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected accommodation types deleted successfully!');
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:accommodation_types,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $order) {
            AccommodationType::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
    }

    /**
     * PUBLIC: Display list of accommodation types
     */
    public function index()
    {
        $accommodationTypes = AccommodationType::active()->ordered()->get();

        return view('accommodation-types.index', compact('accommodationTypes'));
    }

    /**
     * PUBLIC: Show single accommodation type
     */
    public function show(AccommodationType $accommodationType)
    {
        if (!$accommodationType->is_active) {
            abort(404);
        }

        return view('accommodation-types.show', compact('accommodationType'));
    }

    /**
     * API: Get all active accommodation types
     */
    public function getTypes()
    {
        $accommodationTypes = AccommodationType::active()->ordered()->get();
        
        return response()->json($accommodationTypes);
    }

    /**
     * API: Get accommodation type by ID
     */
    public function getById($id)
    {
        $accommodationType = AccommodationType::active()->findOrFail($id);

        return response()->json($accommodationType);
    }
}