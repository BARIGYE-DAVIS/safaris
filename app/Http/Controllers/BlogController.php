<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->where('tags', 'like', "%{$request->tag}%");
        }

        $blogs = $query->latest('published_at')->paginate(12);
        $categories = BlogCategory::orderBy('order')->get();
        
        // Featured blogs for hero section
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

        // Increment views
        $blog->increment('views_count');

        // Related blogs
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
     */
    public function adminIndex()
    {
        $blogs = Blog::with(['category'])
                    ->latest()
                    ->paginate(20);
        
        return view('admin.blogs.index', compact('blogs'));
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
     * Store a newly created blog in database (Admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:1000',
            'content' => 'required',
            'content_json' => 'nullable|json',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable|max:500',
            'tags' => 'nullable|string',
            'author_name' => 'nullable|string|max:255', // FROM FORM INPUT
            'reading_time' => 'nullable|integer',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($request->title);
        
        // NO AUTO AUTHOR - comes from form input only

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blogs/featured', 'public');
            $validated['featured_image'] = $path;
            $validated['og_image'] = $path; // Copy to og_image
        }

        // Auto-set published_at if publishing now
        if ($validated['status'] === 'published' && !$request->published_at) {
            $validated['published_at'] = now();
        }

        // Calculate reading time if not provided
        if (!isset($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($request->content));
            $validated['reading_time'] = ceil($wordCount / 200);
        }

        // Set content_json if provided
        if ($request->has('content_json')) {
            $validated['content_json'] = $request->content_json;
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
     * Update the specified blog in database (Admin)
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:1000',
            'content' => 'required',
            'content_json' => 'nullable|json',
            'category_id' => 'nullable|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable|max:500',
            'tags' => 'nullable|string',
            'author_name' => 'nullable|string|max:255', // FROM FORM INPUT
            'reading_time' => 'nullable|integer',
        ]);

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? true : false;

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $path = $request->file('featured_image')->store('blogs/featured', 'public');
            $validated['featured_image'] = $path;
            $validated['og_image'] = $path; // Copy to og_image
        }

        // Clean up orphaned inline images
        $this->cleanupOrphanedInlineImages($blog->content, $request->content);

        // Update slug
        $validated['slug'] = Str::slug($request->title);

        // Calculate reading time if not provided
        if (!isset($validated['reading_time'])) {
            $wordCount = str_word_count(strip_tags($request->content));
            $validated['reading_time'] = ceil($wordCount / 200);
        }

        // Set content_json if provided
        if ($request->has('content_json')) {
            $validated['content_json'] = $request->content_json;
        }

        $blog->update($validated);

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified blog from database (Admin)
     */
    public function destroy(Blog $blog)
    {
        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        // Delete og_image if different
        if ($blog->og_image && $blog->og_image !== $blog->featured_image) {
            Storage::disk('public')->delete($blog->og_image);
        }

        // Delete all inline images from content
        $this->deleteInlineImagesFromContent($blog->content);

        // Delete blog_images table images
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
     * Upload inline images via Editor (Admin)
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'blog_id' => 'nullable|exists:blogs,id',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:255',
        ]);

        try {
            $path = $request->file('image')->store('blogs/inline', 'public');
            
            // Save to blog_images table if blog_id is provided
            if ($request->has('blog_id')) {
                $this->saveInlineImageRecord(
                    $request->blog_id,
                    $path,
                    $request->alt_text ?? '',
                    $request->caption ?? ''
                );
            }
            
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path),
                'path' => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save inline image record to blog_images table
     */
    private function saveInlineImageRecord($blogId, $path, $alt = '', $caption = '')
    {
        return BlogImage::create([
            'blog_id' => $blogId,
            'image_path' => $path,
            'alt_text' => $alt,
            'caption' => $caption,
            'type' => 'inline',
            'order' => 0,
        ]);
    }

    /**
     * Toggle featured status via AJAX (Admin)
     */
    public function toggleFeatured(Blog $blog)
    {
        $blog->is_featured = !$blog->is_featured;
        $blog->save();

        return response()->json([
            'success' => true,
            'is_featured' => $blog->is_featured,
            'message' => $blog->is_featured ? 'Blog marked as featured' : 'Blog unmarked as featured'
        ]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Extract image paths from HTML content
     */
    private function extractImagePaths($content)
    {
        $images = [];
        
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {
                if (strpos($src, '/storage/blogs/inline/') !== false) {
                    $path = str_replace(asset('storage/'), '', $src);
                    $path = str_replace(url('storage/'), '', $path);
                    $path = preg_replace('/^.*\/storage\//', '', $path);
                    $images[] = $path;
                }
            }
        }
        
        return array_unique($images);
    }

    /**
     * Delete orphaned inline images
     */
    private function cleanupOrphanedInlineImages($oldContent, $newContent)
    {
        $oldImages = $this->extractImagePaths($oldContent);
        $newImages = $this->extractImagePaths($newContent);
        
        $orphanedImages = array_diff($oldImages, $newImages);
        
        foreach ($orphanedImages as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                BlogImage::where('image_path', $imagePath)->delete();
            }
        }
    }

    /**
     * Delete all inline images from content
     */
    private function deleteInlineImagesFromContent($content)
    {
        $images = $this->extractImagePaths($content);
        
        foreach ($images as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
    }
}