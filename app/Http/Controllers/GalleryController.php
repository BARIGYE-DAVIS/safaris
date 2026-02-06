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

    // Category filter
    if ($request->filled('category')) {
        $query->byCategory($request->category);
    }

    $images = $query->paginate(12);
    
    // Get all categories for filter
    $categories = Gallery::visible()
                        ->select('category')
                        ->distinct()
                        ->whereNotNull('category')
                        ->pluck('category')
                        ->toArray();

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
    // Log the incoming request for debugging
    Log::info('Gallery store request received', [
        'has_file' => $request->hasFile('image'),
        'all_data' => $request->except('image')
    ]);

    try {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_images,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
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
            Log::warning('Gallery validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        // Check if image file exists
        if (!$request->hasFile('image')) {
            Log::error('Image file not found in request');
            return back()
                ->withInput()
                ->with('error', 'No image file was uploaded. Please try again.');
        }

        $imageFile = $request->file('image');
        
        // Additional file validation
        if (!$imageFile->isValid()) {
            Log::error('Invalid image file', [
                'error' => $imageFile->getError(),
                'error_message' => $imageFile->getErrorMessage()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'The uploaded file is invalid. Please try again.');
        }

        Log::info('Image file validated', [
            'original_name' => $imageFile->getClientOriginalName(),
            'mime_type' => $imageFile->getMimeType(),
            'size' => $imageFile->getSize()
        ]);

        // Handle image upload
        $imagePath = $this->uploadImage($imageFile);
        
        if (!$imagePath) {
            Log::error('Image upload failed - no path returned');
            return back()
                ->withInput()
                ->with('error', 'Failed to upload image. Please try again.');
        }

        Log::info('Image uploaded successfully', ['path' => $imagePath]);

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
            'tags' => $tags ? json_encode($tags) : null, // Explicitly encode to JSON
            'is_visible' => $request->has('is_visible') ? true : false,
        ]);

        Log::info('Gallery image created successfully', ['id' => $gallery->id]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery image added successfully!');

    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Database error in gallery creation', [
            'message' => $e->getMessage(),
            'sql' => $e->getSql() ?? 'N/A'
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Database error: ' . $e->getMessage());
            
    } catch (\Exception $e) {
        Log::error('Gallery image creation failed', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
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
    /**
 * Private: Upload and optimize image
 */
private function uploadImage($imageFile)
{
    try {
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
        
        Log::info('Attempting to store image', ['filename' => $filename]);
        
        // Store original image
        $path = $imageFile->storeAs('gallery_images', $filename, 'public');
        
        if (!$path) {
            Log::error('Image storage failed - storeAs returned false/null');
            return null;
        }
        
        Log::info('Image stored successfully', ['path' => $path]);

        return $path;
        
    } catch (\Exception $e) {
        Log::error('Image upload exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return null;
    }
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


    /**
 * Admin: Show single gallery image details
 */
public function adminShow(Gallery $gallery)
{
    // Get image file information
    $imagePath = storage_path('app/public/' . $gallery->image_path);
    $imageInfo = [
        'exists' => file_exists($imagePath),
        'size' => file_exists($imagePath) ? filesize($imagePath) : 0,
        'mime_type' => file_exists($imagePath) ? mime_content_type($imagePath) : 'unknown',
    ];

    // Get related images from same category
    $relatedImages = Gallery::where('id', '!=', $gallery->id)
                          ->where('category', $gallery->category)
                          ->limit(6)
                          ->get();

    // Get previous and next images
    $previousImage = Gallery::where('id', '<', $gallery->id)
                          ->orderBy('id', 'desc')
                          ->first();
    
    $nextImage = Gallery::where('id', '>', $gallery->id)
                      ->orderBy('id', 'asc')
                      ->first();

    return view('admin.gallery.show', compact(
        'gallery', 
        'imageInfo', 
        'relatedImages', 
        'previousImage', 
        'nextImage'
    ));
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
    
    return back()->with('success', "Image is now {$status}!");
}
}