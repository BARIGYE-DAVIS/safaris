<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     * Supports search (name/description), sorting and pagination.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $search = $request->query('q');
        $sortBy = $request->query('sort_by', 'order');
        $sortDir = $request->query('sort_dir', 'asc');

        $query = BlogCategory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Allow sorting by a few safe columns only
        $allowedSort = ['id', 'name', 'order', 'created_at', 'updated_at'];
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'order';
        }

        $query->orderBy($sortBy, $sortDir);

        $categories = $query->paginate($perPage)->withQueryString();

        return view('admin.blog_categories.index', compact('categories', 'search', 'sortBy', 'sortDir'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.blog_categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * Accepts:
     * - name (required)
     * - slug (optional)
     * - description (optional)
     * - icon (string, optional) OR icon_upload (file, optional)
     * - order (optional)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'alpha_dash', 'unique:blog_categories,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'icon_upload' => ['nullable', 'file', 'image', 'max:5120'], // 5 MB
            'order' => ['nullable', 'integer'],
        ]);

        // Generate slug if missing
        $slug = $validated['slug'] ?? null;
        if (empty($slug)) {
            $slug = Str::slug($validated['name']);
            // Ensure uniqueness
            $original = $slug;
            $i = 1;
            while (BlogCategory::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $i++;
            }
        }

        // Handle icon upload (optional). If icon_upload provided, store file in public disk.
        $iconValue = $validated['icon'] ?? null;
        if ($request->hasFile('icon_upload')) {
            $path = $request->file('icon_upload')->store('category_icons', 'public');
            // store path relative to storage/app/public
            $iconValue = 'storage/' . $path;
        }

        // If order not provided, place at end (+1)
        $order = $validated['order'] ?? (BlogCategory::max('order') ? BlogCategory::max('order') + 1 : 1);

        $category = BlogCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'icon' => $iconValue,
            'order' => $order,
        ]);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category "' . $category->name . '" created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(BlogCategory $blogCategory)
    {
        // route-model binding will inject the model; view expects $category
        $category = $blogCategory;
        return view('admin.blog_categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * Accepts same inputs as store.
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'alpha_dash', Rule::unique('blog_categories', 'slug')->ignore($blogCategory->id)],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'icon_upload' => ['nullable', 'file', 'image', 'max:5120'], // 5 MB
            'order' => ['nullable', 'integer'],
        ]);

        // Slug handling: if provided use it, else keep existing or generate from name if empty
        $slug = $validated['slug'] ?? $blogCategory->slug;
        if (empty($slug)) {
            $slug = Str::slug($validated['name']);
            $original = $slug;
            $i = 1;
            while (BlogCategory::where('slug', $slug)->where('id', '<>', $blogCategory->id)->exists()) {
                $slug = $original . '-' . $i++;
            }
        }

        // Icon upload handling: if new file uploaded, remove old (if it was stored in storage) and store new
        $iconValue = $validated['icon'] ?? $blogCategory->icon;
        if ($request->hasFile('icon_upload')) {
            // Delete old file if it's a storage path created by this controller: starts with 'storage/'
            if ($blogCategory->icon && Str::startsWith($blogCategory->icon, 'storage/')) {
                $oldPath = Str::after($blogCategory->icon, 'storage/'); // path inside storage/app/public
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('icon_upload')->store('category_icons', 'public');
            $iconValue = 'storage/' . $path;
        }

        $order = $validated['order'] ?? $blogCategory->order;

        $blogCategory->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'icon' => $iconValue,
            'order' => $order,
        ]);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category "' . $blogCategory->name . '" updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        // If icon stored in public disk, delete file
        if ($blogCategory->icon && Str::startsWith($blogCategory->icon, 'storage/')) {
            $oldPath = Str::after($blogCategory->icon, 'storage/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $name = $blogCategory->name;
        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category "' . $name . '" deleted.');
    }

    /**
     * Bulk destroy - accepts array of ids in request('ids').
     */
    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No categories selected for deletion.');
        }

        $categories = BlogCategory::whereIn('id', $ids)->get();

        DB::transaction(function () use ($categories) {
            foreach ($categories as $cat) {
                if ($cat->icon && Str::startsWith($cat->icon, 'storage/')) {
                    $oldPath = Str::after($cat->icon, 'storage/');
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                $cat->delete();
            }
        });

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', count($categories) . ' categories deleted.');
    }

    /**
     * Reorder categories.
     * Expects request()->input('order') to be an array of objects: [{id:1, order:10}, ...]
     */
    public function reorder(Request $request)
    {
        $items = $request->input('order', []);
        if (! is_array($items) || empty($items)) {
            return response()->json(['message' => 'Nothing to reorder.'], 422);
        }

        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                $id = $item['id'] ?? null;
                $order = isset($item['order']) ? (int) $item['order'] : null;
                if ($id && $order !== null) {
                    BlogCategory::where('id', $id)->update(['order' => $order]);
                }
            }
        });

        return response()->json(['message' => 'Order updated.']);
    }

    /**
     * API helper: return categories for select inputs (e.g., select2)
     * ?q=term
     */
    public function apiList(Request $request)
    {
        $term = $request->query('q', null);
        $query = BlogCategory::query();

        if ($term) {
            $query->where('name', 'like', '%' . $term . '%');
        }

        $results = $query->orderBy('order', 'asc')
            ->limit(20)
            ->get(['id', 'name', 'slug']);

        return response()->json($results);
    }
}