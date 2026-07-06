<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\AccommodationImage;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccommodationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC: LIST & DETAIL
    |--------------------------------------------------------------------------
    */

  public function index(Request $request)
{
    $query = Accommodation::query()
        ->where('is_active', true)
        ->with(['country', 'destination', 'images'])
        ->orderBy('sort_order')
        ->orderBy('name');

    // Country filter
    if ($request->filled('country')) {
        $query->whereHas('country', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->country . '%');
        });
    }

    // Type filter
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Search by name (optional)
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $accommodations = $query->paginate(12);
    
    // Get countries for the filter dropdown
    $countries = Country::orderBy('name')->get();

    return view('accommodations.index', compact('accommodations', 'countries'));
}

    public function show(string $slug)
    {
        $accommodation = Accommodation::where('slug', $slug)
            ->where('is_active', true)
            ->with(['country', 'destination', 'images'])
            ->firstOrFail();

        $related = Accommodation::where('id', '!=', $accommodation->id)
            ->where('is_active', true)
            ->when($accommodation->destination_id, function ($q) use ($accommodation) {
                $q->where('destination_id', $accommodation->destination_id);
            })
            ->with(['country', 'destination', 'images'])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('accommodations.show', compact('accommodation', 'related'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: LIST / CREATE / EDIT / DELETE
    |--------------------------------------------------------------------------
    */

    public function adminIndex(Request $request)
    {
        $query = Accommodation::query()
            ->with(['country', 'destination', 'images'])
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $accommodations = $query->paginate(20);
        $countries      = Country::orderBy('name')->get();
        $destinations   = Destination::orderBy('name')->get();

        return view('admin.accommodations.index', compact('accommodations', 'countries', 'destinations'));
    }

    public function adminCreate()
    {
        $countries    = Country::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();

        return view('admin.accommodations.create', compact('countries', 'destinations'));
    }

    public function adminStore(Request $request)
    {
        Log::info('Accommodation adminStore called', [
            'has_gallery' => $request->hasFile('gallery_images'),
            'gallery_count' => $request->hasFile('gallery_images')
                ? count($request->file('gallery_images'))
                : 0,
        ]);

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'nullable|string|max:100',
            'location'          => 'nullable|string|max:255',
            'country_id'        => 'nullable|exists:countries,id',
            'destination_id'    => 'nullable|exists:destinations,id',
            'category'          => 'nullable|string|max:50',
            'currency'          => 'nullable|string|max:10',
            'price_from'        => 'nullable|numeric|min:0',
            'price_to'          => 'nullable|numeric|min:0|gte:price_from',
            'short_description' => 'nullable|string',
            'full_description'  => 'nullable|string',
            'is_active'         => 'sometimes|boolean',
            'is_featured'       => 'sometimes|boolean',
            'sort_order'        => 'nullable|integer|min:0',
            'featured_image'    => 'nullable|image|max:4096',
            'amenities_list'    => 'nullable|array',
            'amenities_list.*'  => 'string|max:255',
            'gallery_images.*'  => 'nullable|image|max:4096',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'focus_keyword'     => 'nullable|string|max:255',
            'blocks'            => 'nullable|array',
            'blocks.*.type'     => 'required|in:text,heading,image,list,table,buttons',
            'blocks.*.content'  => 'nullable|string',
            'blocks.*.heading_level' => 'nullable|in:h1,h2,h3,h4,h5,h6',
            'blocks.*.list_type' => 'nullable|in:ul,ol',
            'blocks.*.caption'          => 'nullable|string',
            'blocks.*.headers'          => 'nullable|array',
            'blocks.*.rows'             => 'nullable|array',
            'blocks.*.striped'          => 'nullable|boolean',
            'blocks.*.bordered'         => 'nullable|boolean',
            'blocks.*.hoverable'        => 'nullable|boolean',
            'blocks.*.small'            => 'nullable|boolean',
            'blocks.*.header_bg_color'  => 'nullable|string',
            'blocks.*.header_text_color'=> 'nullable|string',
            'blocks.*.row_bg_color'     => 'nullable|string',
            'blocks.*.row_bg_alt_color' => 'nullable|string',
            'blocks.*.row_text_color'   => 'nullable|string',
            'blocks.*.border_color'     => 'nullable|string',
            'blocks.*.buttons'          => 'nullable|array',
            'blocks.*.alignment'        => 'nullable|in:left,center,right,justify',
            'blocks.*.direction'        => 'nullable|in:horizontal,vertical',
            'blocks.*.gap'              => 'nullable|in:small,medium,large',
            'blocks.*.default_bg_color' => 'nullable|string',
            'blocks.*.default_text_color' => 'nullable|string',
            'blocks.*.default_hover_bg_color' => 'nullable|string',
            'blocks.*.default_border_radius' => 'nullable|string',
            'blocks.*.buttons.*.text'   => 'nullable|string',
            'blocks.*.buttons.*.url'    => 'nullable|url|max:500',
            'blocks.*.buttons.*.bg_color' => 'nullable|string',
            'blocks.*.buttons.*.text_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_bg_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_text_color' => 'nullable|string',
            'blocks.*.buttons.*.border_radius' => 'nullable|string',
            'blocks.*.buttons.*.size'   => 'nullable|in:small,medium,large',
            'blocks.*.buttons.*.type'   => 'nullable|in:primary,secondary,outline,ghost',
            'blocks.*.buttons.*.icon'   => 'nullable|string',
            'blocks.*.buttons.*.target' => 'nullable|string',
            'blocks.*.buttons.*.rel'    => 'nullable|string',
        ]);

        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if (empty($data['currency'])) {
            $data['currency'] = 'USD';
        }

        // Generate slug WITHOUT random letters
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Accommodation::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data['slug'] = $slug;

        $data['amenities'] = $request->filled('amenities_list')
            ? $request->input('amenities_list')
            : null;

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            try {
                $data['featured_image'] = $request->file('featured_image')
                    ->store('accommodations/featured', 'public');
            } catch (\Throwable $e) {
                Log::error('Error storing featured image in adminStore', [
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]);
            }
        }

        $accommodation = Accommodation::create($data);

        // Process content blocks
        if ($request->has('blocks') && is_array($request->blocks)) {
            $contentBlocks = $this->processBlocks($request->blocks, $accommodation->id);
            $accommodation->update(['content_blocks' => $contentBlocks]);
        }

        // GALLERY IMAGES
        try {
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $file) {
                    Log::info('Storing gallery image (create)', [
                        'accommodation_id' => $accommodation->id,
                        'index'            => $index,
                        'original_name'    => $file->getClientOriginalName(),
                        'size'             => $file->getSize(),
                    ]);

                    $path = $file->store('accommodations/gallery', 'public');

                    AccommodationImage::create([
                        'accommodation_id' => $accommodation->id,
                        'path'             => $path,
                        'caption'          => null,
                        'alt_text'         => $accommodation->name . ' image',
                        'sort_order'       => $index,
                    ]);
                }
            } else {
                Log::info('No gallery_images files found in adminStore');
            }
        } catch (\Throwable $e) {
            Log::error('Error storing gallery images in adminStore', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        return redirect()
            ->route('admin.accommodations.index')
            ->with('success', 'Accommodation created successfully.');
    }

    public function adminEdit(Accommodation $accommodation)
    {
        $countries    = Country::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();

        $accommodation->load('images');

        return view('admin.accommodations.edit', compact('accommodation', 'countries', 'destinations'));
    }

    public function adminUpdate(Request $request, Accommodation $accommodation)
    {
        Log::info('Accommodation adminUpdate called', [
            'accommodation_id' => $accommodation->id,
            'has_gallery'      => $request->hasFile('gallery_images'),
            'gallery_count'    => $request->hasFile('gallery_images')
                ? count($request->file('gallery_images'))
                : 0,
            'delete_images'    => $request->input('delete_images', []),
        ]);

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'nullable|string|max:100',
            'location'          => 'nullable|string|max:255',
            'country_id'        => 'nullable|exists:countries,id',
            'destination_id'    => 'nullable|exists:destinations,id',
            'category'          => 'nullable|string|max:50',
            'currency'          => 'nullable|string|max:10',
            'price_from'        => 'nullable|numeric|min:0',
            'price_to'          => 'nullable|numeric|min:0|gte:price_from',
            'short_description' => 'nullable|string',
            'full_description'  => 'nullable|string',
            'is_active'         => 'sometimes|boolean',
            'is_featured'       => 'sometimes|boolean',
            'sort_order'        => 'nullable|integer|min:0',
            'featured_image'    => 'nullable|image|max:4096',
            'amenities_list'    => 'nullable|array',
            'amenities_list.*'  => 'string|max:255',
            'gallery_images.*'  => 'nullable|image|max:4096',
            'delete_images'     => 'nullable|array',
            'delete_images.*'   => 'integer|exists:accommodation_images,id',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'focus_keyword'     => 'nullable|string|max:255',
            'blocks'            => 'nullable|array',
            'blocks.*.type'     => 'required|in:text,heading,image,list,table,buttons',
            'blocks.*.content'  => 'nullable|string',
            'blocks.*.heading_level' => 'nullable|in:h1,h2,h3,h4,h5,h6',
            'blocks.*.list_type' => 'nullable|in:ul,ol',
            'blocks.*.caption'          => 'nullable|string',
            'blocks.*.headers'          => 'nullable|array',
            'blocks.*.rows'             => 'nullable|array',
            'blocks.*.striped'          => 'nullable|boolean',
            'blocks.*.bordered'         => 'nullable|boolean',
            'blocks.*.hoverable'        => 'nullable|boolean',
            'blocks.*.small'            => 'nullable|boolean',
            'blocks.*.header_bg_color'  => 'nullable|string',
            'blocks.*.header_text_color'=> 'nullable|string',
            'blocks.*.row_bg_color'     => 'nullable|string',
            'blocks.*.row_bg_alt_color' => 'nullable|string',
            'blocks.*.row_text_color'   => 'nullable|string',
            'blocks.*.border_color'     => 'nullable|string',
            'blocks.*.buttons'          => 'nullable|array',
            'blocks.*.alignment'        => 'nullable|in:left,center,right,justify',
            'blocks.*.direction'        => 'nullable|in:horizontal,vertical',
            'blocks.*.gap'              => 'nullable|in:small,medium,large',
            'blocks.*.default_bg_color' => 'nullable|string',
            'blocks.*.default_text_color' => 'nullable|string',
            'blocks.*.default_hover_bg_color' => 'nullable|string',
            'blocks.*.default_border_radius' => 'nullable|string',
            'blocks.*.buttons.*.text'   => 'nullable|string',
            'blocks.*.buttons.*.url'    => 'nullable|url|max:500',
            'blocks.*.buttons.*.bg_color' => 'nullable|string',
            'blocks.*.buttons.*.text_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_bg_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_text_color' => 'nullable|string',
            'blocks.*.buttons.*.border_radius' => 'nullable|string',
            'blocks.*.buttons.*.size'   => 'nullable|in:small,medium,large',
            'blocks.*.buttons.*.type'   => 'nullable|in:primary,secondary,outline,ghost',
            'blocks.*.buttons.*.icon'   => 'nullable|string',
            'blocks.*.buttons.*.target' => 'nullable|string',
            'blocks.*.buttons.*.rel'    => 'nullable|string',
        ]);

        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if (empty($data['currency'])) {
            $data['currency'] = $accommodation->currency ?? 'USD';
        }

        // Only generate slug if it's empty (preserve existing slug)
        if (empty($accommodation->slug)) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $counter = 1;

            while (Accommodation::where('slug', $slug)->where('id', '!=', $accommodation->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $data['slug'] = $slug;
        }

        $data['amenities'] = $request->filled('amenities_list')
            ? $request->input('amenities_list')
            : null;

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            try {
                if ($accommodation->featured_image) {
                    Storage::disk('public')->delete($accommodation->featured_image);
                }

                $data['featured_image'] = $request->file('featured_image')
                    ->store('accommodations/featured', 'public');
            } catch (\Throwable $e) {
                Log::error('Error storing featured image in adminUpdate', [
                    'accommodation_id' => $accommodation->id,
                    'message'          => $e->getMessage(),
                    'trace'            => $e->getTraceAsString(),
                ]);
            }
        }

        $accommodation->update($data);

        // ============================================
        // DELETE GALLERY IMAGES (direct from form)
        // ============================================
        if ($request->filled('delete_images')) {
            $ids = $request->input('delete_images');
            
            // Ensure it's an array
            if (!is_array($ids)) {
                $ids = [$ids];
            }
            
            // Filter out empty values
            $ids = array_filter($ids);
            
            if (!empty($ids)) {
                Log::info('Deleting gallery images', [
                    'accommodation_id' => $accommodation->id,
                    'ids' => $ids
                ]);
                
                $imagesToDelete = $accommodation->images()->whereIn('id', $ids)->get();
                
                foreach ($imagesToDelete as $img) {
                    try {
                        if (Storage::disk('public')->exists($img->path)) {
                            Storage::disk('public')->delete($img->path);
                        }
                        $img->delete();
                        Log::info('Deleted gallery image', ['id' => $img->id]);
                    } catch (\Exception $e) {
                        Log::error('Error deleting gallery image', [
                            'id' => $img->id,
                            'message' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // ============================================
        // PROCESS CONTENT BLOCKS (including delete_images)
        // ============================================
        if ($request->has('blocks') && is_array($request->blocks)) {
            $contentBlocks = $this->processBlocks($request->blocks, $accommodation->id);
            $accommodation->update(['content_blocks' => $contentBlocks]);
        }

        // ADD NEW GALLERY IMAGES
        try {
            if ($request->hasFile('gallery_images')) {
                $currentMaxOrder = $accommodation->images()->max('sort_order') ?? 0;

                foreach ($request->file('gallery_images') as $i => $file) {
                    Log::info('Storing gallery image (update)', [
                        'accommodation_id' => $accommodation->id,
                        'index'            => $i,
                        'original_name'    => $file->getClientOriginalName(),
                        'size'             => $file->getSize(),
                    ]);

                    $path = $file->store('accommodations/gallery', 'public');

                    AccommodationImage::create([
                        'accommodation_id' => $accommodation->id,
                        'path'             => $path,
                        'caption'          => null,
                        'alt_text'         => $accommodation->name . ' image',
                        'sort_order'       => $currentMaxOrder + $i + 1,
                    ]);
                }
            } else {
                Log::info('No gallery_images files found in adminUpdate', [
                    'accommodation_id' => $accommodation->id,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error storing gallery images in adminUpdate', [
                'accommodation_id' => $accommodation->id,
                'message'          => $e->getMessage(),
                'trace'            => $e->getTraceAsString(),
            ]);
        }

        return redirect()
            ->route('admin.accommodations.edit', $accommodation)
            ->with('success', 'Accommodation updated successfully.');
    }

    public function adminDestroy(Accommodation $accommodation)
    {
        if ($accommodation->featured_image) {
            Storage::disk('public')->delete($accommodation->featured_image);
        }

        $accommodation->loadMissing('images');

        foreach ($accommodation->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $accommodation->delete();

        return redirect()
            ->route('admin.accommodations.index')
            ->with('success', 'Accommodation deleted successfully.');
    }

    /**
     * API endpoint for searching accommodations
     */
    public function apiSearch(Request $request)
    {
        $query = Accommodation::query()->where('is_active', true);

        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->input('destination_id'));
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        $accommodations = $query->with('images')
                               ->orderBy('name')
                               ->limit(50)
                               ->get();

        return response()->json([
            'success' => true,
            'data' => $accommodations->map(function ($accommodation) {
                return [
                    'id' => $accommodation->id,
                    'name' => $accommodation->name,
                    'type' => $accommodation->type,
                    'category' => $accommodation->category,
                    'location' => $accommodation->location,
                    'featured_image' => $accommodation->featured_image_url,
                    'images' => $accommodation->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'path' => $image->path,
                            'url' => $image->url ?? asset('storage/' . $image->path),
                            'caption' => $image->caption,
                            'alt_text' => $image->alt_text,
                            'sort_order' => $image->sort_order,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * API endpoint to get a single accommodation with its images
     */
    public function apiGetById($id)
    {
        $accommodation = Accommodation::with('images')->find($id);

        if (!$accommodation) {
            return response()->json([
                'success' => false,
                'message' => 'Accommodation not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $accommodation->id,
                'name' => $accommodation->name,
                'type' => $accommodation->type,
                'category' => $accommodation->category,
                'location' => $accommodation->location,
                'featured_image' => $accommodation->featured_image_url,
                'images' => $accommodation->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                        'url' => $image->url ?? asset('storage/' . $image->path),
                        'caption' => $image->caption,
                        'alt_text' => $image->alt_text,
                        'sort_order' => $image->sort_order,
                    ];
                }),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Process content blocks from the request
     */
    private function processBlocks(array $blocksData, int $accommodationId): array
    {
        $processedBlocks = [];
        $sortOrder = 0;

        foreach ($blocksData as $blockData) {
            $type = $blockData['type'] ?? 'text';
            $blockId = $blockData['id'] ?? 'blk-' . Str::random(8);
            $blockData['id'] = $blockId;

            if ($type === 'image') {
                // ============================================
                // FIX: DELETE IMAGES MARKED FOR DELETION
                // ============================================
                $deleteIds = $blockData['delete_images'] ?? [];
                $deleteIds = array_filter($deleteIds);
                
                if (!empty($deleteIds)) {
                    Log::info('Deleting block images', [
                        'accommodation_id' => $accommodationId,
                        'block_id' => $blockId,
                        'image_ids' => $deleteIds
                    ]);
                    
                    foreach ($deleteIds as $imageId) {
                        try {
                            $image = AccommodationImage::find($imageId);
                            if ($image && $image->accommodation_id == $accommodationId) {
                                // Delete physical file
                                if (Storage::disk('public')->exists($image->path)) {
                                    Storage::disk('public')->delete($image->path);
                                }
                                // Delete database record
                                $image->delete();
                                Log::info('Deleted block image', ['id' => $imageId]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Error deleting block image', [
                                'id' => $imageId,
                                'message' => $e->getMessage()
                            ]);
                        }
                    }
                }

                // Handle new image uploads for this block
                $this->syncBlockImages($accommodationId, $blockData, $sortOrder);

                $processedBlocks[] = [
                    'type' => 'image',
                    'id' => $blockId,
                    'caption' => $blockData['caption'] ?? null,
                ];
            } 
            elseif ($type === 'table') {
                $processedBlocks[] = [
                    'type' => 'table',
                    'id' => $blockId,
                    'caption' => $blockData['caption'] ?? null,
                    'headers' => $blockData['headers'] ?? [],
                    'rows' => $blockData['rows'] ?? [],
                    'striped' => isset($blockData['striped']),
                    'bordered' => isset($blockData['bordered']) ? true : false,
                    'hoverable' => isset($blockData['hoverable']),
                    'small' => isset($blockData['small']),
                    'header_bg_color' => $blockData['header_bg_color'] ?? '#f3f4f6',
                    'header_text_color' => $blockData['header_text_color'] ?? '#111827',
                    'row_bg_color' => $blockData['row_bg_color'] ?? '#ffffff',
                    'row_bg_alt_color' => $blockData['row_bg_alt_color'] ?? '#f9fafb',
                    'row_text_color' => $blockData['row_text_color'] ?? '#111827',
                    'border_color' => $blockData['border_color'] ?? '#d1d5db',
                ];
            } 
            elseif ($type === 'buttons') {
                $buttons = [];
                if (!empty($blockData['buttons']) && is_array($blockData['buttons'])) {
                    foreach ($blockData['buttons'] as $btn) {
                        $buttons[] = [
                            'text' => $btn['text'] ?? 'Button',
                            'url' => $btn['url'] ?? '#',
                            'bg_color' => $btn['bg_color'] ?? '#2563eb',
                            'text_color' => $btn['text_color'] ?? '#ffffff',
                            'hover_bg_color' => $btn['hover_bg_color'] ?? '#1d4ed8',
                            'hover_text_color' => $btn['hover_text_color'] ?? '#ffffff',
                            'border_radius' => $btn['border_radius'] ?? '8px',
                            'size' => $btn['size'] ?? 'medium',
                            'type' => $btn['type'] ?? 'primary',
                            'icon' => $btn['icon'] ?? null,
                            'target' => $btn['target'] ?? '_self',
                            'rel' => $btn['rel'] ?? '',
                        ];
                    }
                }

                $processedBlocks[] = [
                    'type' => 'buttons',
                    'id' => $blockId,
                    'buttons' => $buttons,
                    'alignment' => $blockData['alignment'] ?? 'left',
                    'direction' => $blockData['direction'] ?? 'horizontal',
                    'gap' => $blockData['gap'] ?? 'medium',
                    'default_bg_color' => $blockData['default_bg_color'] ?? '#2563eb',
                    'default_text_color' => $blockData['default_text_color'] ?? '#ffffff',
                    'default_hover_bg_color' => $blockData['default_hover_bg_color'] ?? '#1d4ed8',
                    'default_hover_text_color' => $blockData['default_hover_text_color'] ?? '#ffffff',
                    'default_border_radius' => $blockData['default_border_radius'] ?? '8px',
                ];
            } 
            else {
                // Text, heading, list blocks
                $processedBlocks[] = $blockData;
            }

            $sortOrder++;
        }

        return $processedBlocks;
    }

    /**
     * Sync images for a block
     */
    private function syncBlockImages(int $accommodationId, array $blockData, int $sortOrder): void
    {
        $newImages = $blockData['images'] ?? [];
        $altTexts = $blockData['alts'] ?? [];
        $blockId = $blockData['id'] ?? 'blk-' . Str::random(8);

        if (!empty($newImages) && is_array($newImages)) {
            foreach ($newImages as $i => $newImage) {
                if ($newImage && $newImage instanceof \Illuminate\Http\UploadedFile && $newImage->isValid()) {
                    try {
                        $path = $newImage->store('accommodations/blocks', 'public');
                        $altText = $altTexts[$i] ?? null;

                        AccommodationImage::create([
                            'accommodation_id' => $accommodationId,
                            'block_id' => $blockId,
                            'path' => $path,
                            'caption' => $blockData['caption'] ?? null,
                            'alt_text' => $altText,
                            'sort_order' => $i,
                        ]);

                        Log::info('Image saved successfully for accommodation block', [
                            'block_id' => $blockId,
                            'path' => $path,
                            'accommodation_id' => $accommodationId
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Error saving image for accommodation block', [
                            'error' => $e->getMessage(),
                            'image_index' => $i
                        ]);
                        continue;
                    }
                }
            }
        }
    }
}