<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Public: Display gallery for visitors
     */
    public function index(Request $request)
    {
        $query = Gallery::visible()->latest();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $images = $query->paginate(12);
        $categories = Gallery::visible()
                            ->select('category')
                            ->distinct()
                            ->whereNotNull('category')
                            ->pluck('category')
                            ->sort();

        return view('gallery.index', compact('images', 'categories'));
    }

    /**
     * Public: Show single gallery image
     */
    public function show(Gallery $gallery)
    {
        // Only show visible images to public
        if (!$gallery->is_visible) {
            abort(404);
        }

        // Get related images from same category
        $relatedImages = Gallery::visible()
                              ->where('id', '!=', $gallery->id)
                              ->where('category', $gallery->category)
                              ->limit(6)
                              ->get();

        return view('gallery.show', compact('gallery', 'relatedImages'));
    }

    /**
     * Admin: Display all gallery images
     */
    public function admin(Request $request)
    {
        $query = Gallery::latest();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by visibility
        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible === '1');
        }

        $images = $query->paginate(20);
        
        // Get all categories for filter
        $categories = Gallery::select('category')
                           ->distinct()
                           ->whereNotNull('category')
                           ->pluck('category')
                           ->sort();

        return view('admin.gallery.index', compact('images', 'categories'));
    }

    /**
     * Admin: Show form for creating new gallery image
     */
    public function create()
    {
        $categories = [
            'wildlife' => 'Wildlife',
            'landscapes' => 'Landscapes',
            'culture' => 'Culture & People',
            'accommodation' => 'Accommodation',
            'activities' => 'Activities',
            'food' => 'Food & Dining',
            'transport' => 'Transportation',
            'behind_scenes' => 'Behind the Scenes',
        ];

        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Admin: Store new gallery image
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:gallery_images,slug',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB max
                'caption' => 'nullable|string|max:1000',
                'alt_text' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'category' => 'required|string|max:100',
                'tags' => 'nullable|string|max:1000',
                'is_visible' => 'boolean',
            ], [
                'title.required' => 'Please enter an image title.',
                'image.required' => 'Please select an image to upload.',
                'image.image' => 'Please upload a valid image file.',
                'image.mimes' => 'Image must be jpeg, png, jpg, or webp format.',
                'image.max' => 'Image size must not exceed 10MB.',
                'category.required' => 'Please select a category.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the errors below.');
            }

            // Handle image upload
            $imagePath = $this->uploadImage($request->file('image'));

            // Process tags
            $tags = $this->processTags($request->tags);

            // Create gallery entry
            $gallery = Gallery::create([
                'title' => $request->title,
                'slug' => $request->slug ?: Gallery::generateUniqueSlug($request->title),
                'image_path' => $imagePath,
                'caption' => $request->caption,
                'alt_text' => $request->alt_text ?: $request->title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'category' => $request->category,
                'tags' => $tags,
                'is_visible' => $request->boolean('is_visible', true),
            ]);

            return redirect()
                ->route('admin.gallery.index')
                ->with('success', 'Gallery image added successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery image creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to add gallery image. Error: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Show form for editing gallery image
     */
    public function edit(Gallery $gallery)
    {
        $categories = [
            'wildlife' => 'Wildlife',
            'landscapes' => 'Landscapes',
            'culture' => 'Culture & People',
            'accommodation' => 'Accommodation',
            'activities' => 'Activities',
            'food' => 'Food & Dining',
            'transport' => 'Transportation',
            'behind_scenes' => 'Behind the Scenes',
        ];

        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Admin: Update gallery image
     */
    public function update(Request $request, Gallery $gallery)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:gallery_images,slug,' . $gallery->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'caption' => 'nullable|string|max:1000',
                'alt_text' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'category' => 'required|string|max:100',
                'tags' => 'nullable|string|max:1000',
                'is_visible' => 'boolean',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the errors below.');
            }

            $data = [
                'title' => $request->title,
                'slug' => $request->slug ?: Gallery::generateUniqueSlug($request->title, $gallery->id),
                'caption' => $request->caption,
                'alt_text' => $request->alt_text ?: $request->title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'category' => $request->category,
                'tags' => $this->processTags($request->tags),
                'is_visible' => $request->boolean('is_visible', true),
            ];

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image_path) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                
                // Upload new image
                $data['image_path'] = $this->uploadImage($request->file('image'));
            }

            $gallery->update($data);

            return redirect()
                ->route('admin.gallery.index')
                ->with('success', 'Gallery image updated successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery image update failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update gallery image. Please try again.');
        }
    }

    /**
     * Admin: Delete gallery image
     */
    public function destroy(Gallery $gallery)
    {
        try {
            // Delete image file
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            // Delete record
            $gallery->delete();

            return back()->with('success', 'Gallery image deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Gallery image deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete gallery image. Please try again.');
        }
    }

    /**
     * Admin: Toggle visibility
     */
    public function toggleVisibility(Gallery $gallery)
    {
        $gallery->update([
            'is_visible' => !$gallery->is_visible
        ]);

        $status = $gallery->is_visible ? 'visible' : 'hidden';
        
        return response()->json([
            'success' => true,
            'message' => "Image is now {$status}",
            'is_visible' => $gallery->is_visible
        ]);
    }

    /**
     * Admin: Bulk delete images
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_ids' => 'required|array',
            'image_ids.*' => 'exists:gallery_images,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $images = Gallery::whereIn('id', $request->image_ids)->get();

            foreach ($images as $image) {
                // Delete image file
                if ($image->image_path) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Delete records
            Gallery::whereIn('id', $request->image_ids)->delete();

            return back()->with('success', count($request->image_ids) . ' images deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Bulk gallery deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete selected images. Please try again.');
        }
    }

    /**
     * Admin: Export gallery data to CSV
     */
    public function export(Request $request)
    {
        $query = Gallery::query();

        // Apply filters if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible === '1');
        }

        $images = $query->latest()->get();

        $filename = 'gallery-images-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($images) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Title', 'Slug', 'Category', 'Caption', 'Alt Text',
                'Meta Description', 'Meta Keywords', 'Tags', 'Is Visible', 
                'Created At'
            ]);

            // CSV data
            foreach ($images as $image) {
                fputcsv($file, [
                    $image->id,
                    $image->title,
                    $image->slug,
                    $image->category,
                    $image->caption,
                    $image->alt_text,
                    $image->meta_description,
                    $image->meta_keywords,
                    implode(', ', $image->formatted_tags),
                    $image->is_visible ? 'Yes' : 'No',
                    $image->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get gallery statistics
     */
    public function getStats()
    {
        return [
            'total' => Gallery::count(),
            'visible' => Gallery::where('is_visible', true)->count(),
            'hidden' => Gallery::where('is_visible', false)->count(),
            'by_category' => Gallery::selectRaw('category, COUNT(*) as count')
                                  ->groupBy('category')
                                  ->pluck('count', 'category')
                                  ->toArray(),
            'recent' => Gallery::where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }

    /**
     * Private: Upload and optimize image
     */
    private function uploadImage($imageFile)
    {
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
        
        // Store original image
        $path = $imageFile->storeAs('gallery_images', $filename, 'public');

        // Optional: Optimize image using Intervention Image (if you have it installed)
        try {
            $fullPath = storage_path('app/public/' . $path);
            $image = Image::make($fullPath);
            
            // Resize if too large (max width 1920px)
            if ($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Optimize quality
            $image->save($fullPath, 85);
            
        } catch (\Exception $e) {
            // Continue if image optimization fails
            Log::warning('Image optimization failed: ' . $e->getMessage());
        }

        return $path;
    }

    /**
     * Private: Process tags string into array
     */
    private function processTags($tagsString)
    {
        if (!$tagsString) {
            return null;
        }

        return collect(explode(',', $tagsString))
                ->map(function($tag) {
                    return trim($tag);
                })
                ->filter(function($tag) {
                    return !empty($tag);
                })
                ->unique()
                ->values()
                ->all();
    }
}