<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // ==================== PUBLIC METHODS ====================

    /**
     * Display a listing of blogs (Public)
     */
    public function index(Request $request)
    {
        $query = Blog::with(['category'])
                    ->where('status', 'published')
                    ->where('published_at', '<=', now());

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->has('tag')) {
            $query->where('tags', 'like', "%{$request->tag}%");
        }

        $blogs      = $query->latest('published_at')->paginate(12);
        $categories = BlogCategory::orderBy('order')->get();

        $featuredBlogs = Blog::published()
                            ->featured()
                            ->latest('published_at')
                            ->limit(5)
                            ->get();

        return view('blogs.index', compact('blogs', 'categories', 'featuredBlogs'));
    }

    /**
     * Display a single blog post (Public)
     */
    public function show($slug)
    {
        $blog = Blog::with(['category', 'images'])
                    ->where('slug', $slug)
                    ->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->firstOrFail();

        $blog->increment('views_count');

        $relatedBlogs = Blog::where('status', 'published')
                           ->where('published_at', '<=', now())
                           ->where('category_id', $blog->category_id)
                           ->where('id', '!=', $blog->id)
                           ->latest('published_at')
                           ->limit(3)
                           ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Display a listing of all blogs in admin (Admin)
     *
     * FIX: Pass $publishedCount, $featuredCount and $totalViews that the
     *      admin index blade references via {{ $publishedCount ?? 0 }} etc.
     *      Previously these were never passed so the stats cards always
     *      showed zero regardless of the actual database values.
     */
    public function adminIndex()
    {
        $blogs = Blog::with(['category'])
                    ->latest()
                    ->paginate(20);

        // FIX: compute the three stats the blade needs
        $publishedCount = Blog::where('status', 'published')->count();
        $featuredCount  = Blog::where('is_featured', true)->count();
        $totalViews     = Blog::sum('views_count') ?? 0;

        return view('admin.blogs.index', compact(
            'blogs',
            'publishedCount',
            'featuredCount',
            'totalViews'
        ));
    }

    /**
     * Show the form for creating a new blog (Admin)
     */
    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created blog (Admin)
     *
     * FIX: Auto-set published_at to now() when status is 'published' and
     *      no date was explicitly supplied.  Previously published_at stayed
     *      NULL, and the scopePublished() query requires published_at <= now(),
     *      so every blog with a NULL published_at was invisible on the public
     *      site even when its status column said 'published'.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|max:255',
            'excerpt'         => 'nullable|max:1000',
            'content'         => 'required',
            'content_json'    => 'nullable|string',
            'category_id'     => 'nullable|exists:blog_categories,id',
            'featured_image'  => 'nullable|image|max:5120',
            'status'          => 'required|in:draft,published,scheduled',
            'published_at'    => 'nullable|date',
            'is_featured'     => 'nullable|boolean',
            'meta_title'      => 'nullable|max:255',
            'meta_description'=> 'nullable|max:500',
            'meta_keywords'   => 'nullable|max:500',
            'tags'            => 'nullable|string',
            'author_name'     => 'nullable|string|max:255',
            'reading_time'    => 'nullable|integer',
        ]);

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blogs/featured', 'public');
            $validated['featured_image'] = $path;
            $validated['og_image']       = $path;
        }

        // Generate slug from title
        $validated['slug'] = Str::slug($request->title);

        // FIX: Auto-set published_at when publishing without an explicit date.
        //      Without this, published_at is NULL and scopePublished() hides
        //      the post because NULL <= now() evaluates to false in MySQL.
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Auto-calculate reading time if not supplied
        if (empty($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($request->content));
            $validated['reading_time'] = max(1, (int) ceil($wordCount / 200));
        }

        // Validate and store content_json
        if ($request->filled('content_json')) {
            try {
                $decoded = json_decode($request->content_json, true);
                $validated['content_json'] = json_last_error() === JSON_ERROR_NONE
                    ? json_encode($decoded)
                    : null;
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning('Invalid content_json on store', ['error' => json_last_error_msg()]);
                }
            } catch (\Exception $e) {
                Log::error('Error processing content_json on store: ' . $e->getMessage());
                $validated['content_json'] = null;
            }
        } else {
            $validated['content_json'] = null;
        }

        $blog = Blog::create($validated);

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Show the form for editing a blog (Admin)
     */
    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog (Admin)
     *
     * FIX: Same published_at auto-set as store() — if someone changes an
     *      existing draft to 'published' without picking a date, the post
     *      would remain hidden on the public site without this fix.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'           => 'required|max:255',
            'excerpt'         => 'nullable|max:1000',
            'content'         => 'required',
            'content_json'    => 'nullable|string',
            'category_id'     => 'nullable|exists:blog_categories,id',
            'featured_image'  => 'nullable|image|max:5120',
            'status'          => 'required|in:draft,published,scheduled',
            'published_at'    => 'nullable|date',
            'is_featured'     => 'nullable|boolean',
            'meta_title'      => 'nullable|max:255',
            'meta_description'=> 'nullable|max:500',
            'meta_keywords'   => 'nullable|max:500',
            'tags'            => 'nullable|string',
            'author_name'     => 'nullable|string|max:255',
            'reading_time'    => 'nullable|integer',
        ]);

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle featured image replacement
        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $path = $request->file('featured_image')->store('blogs/featured', 'public');
            $validated['featured_image'] = $path;
            $validated['og_image']       = $path;
        }

        // Clean up orphaned inline images from the old content
        $this->cleanupOrphanedInlineImages($blog->content, $request->content);

        // Regenerate slug from (possibly updated) title
        $validated['slug'] = Str::slug($request->title);

        // FIX: Auto-set published_at when changing status to 'published'
        //      and no date was supplied AND the blog had no prior published_at.
        if (
            $validated['status'] === 'published' &&
            empty($validated['published_at']) &&
            empty($blog->published_at)
        ) {
            $validated['published_at'] = now();
        }

        // Auto-calculate reading time if not supplied
        if (empty($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($request->content));
            $validated['reading_time'] = max(1, (int) ceil($wordCount / 200));
        }

        // Validate and store content_json
        if ($request->filled('content_json')) {
            try {
                $decoded = json_decode($request->content_json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated['content_json'] = json_encode($decoded);
                } else {
                    Log::warning('Invalid content_json on update', ['error' => json_last_error_msg()]);
                    unset($validated['content_json']); // keep existing value
                }
            } catch (\Exception $e) {
                Log::error('Error processing content_json on update: ' . $e->getMessage());
                unset($validated['content_json']); // keep existing value
            }
        } else {
            unset($validated['content_json']); // keep existing value
        }

        $blog->update($validated);

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified blog (Admin)
     */
    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        if ($blog->og_image && $blog->og_image !== $blog->featured_image) {
            Storage::disk('public')->delete($blog->og_image);
        }

        $this->deleteInlineImagesFromContent($blog->content);

        foreach ($blog->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully!');
    }

    /**
     * Toggle featured status via AJAX (Admin)
     * Called by the toggle-featured buttons in admin index.
     */
    public function toggleFeatured(Blog $blog)
    {
        $blog->update(['is_featured' => !$blog->is_featured]);

        return response()->json([
            'success'     => true,
            'is_featured' => $blog->is_featured,
        ]);
    }

    /**
     * Upload an inline image for blog content (AJAX)
     */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $path = $request->file('image')->store('blogs/inline', 'public');

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
            'path'    => $path,
        ]);
    }

    // ==================== PRIVATE HELPERS ====================

    private function cleanupOrphanedInlineImages($oldContent, $newContent)
    {
        preg_match_all('/storage\/blogs\/inline\/[^\s\'"]+/', $oldContent ?? '', $oldImages);
        preg_match_all('/storage\/blogs\/inline\/[^\s\'"]+/', $newContent ?? '', $newImages);

        $orphaned = array_diff($oldImages[0] ?? [], $newImages[0] ?? []);
        foreach ($orphaned as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    private function deleteInlineImagesFromContent($content)
    {
        preg_match_all('/storage\/blogs\/inline\/[^\s\'"]+/', $content ?? '', $images);
        foreach ($images[0] ?? [] as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}