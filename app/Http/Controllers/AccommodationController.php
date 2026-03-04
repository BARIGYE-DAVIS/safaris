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

        if ($request->filled('country')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('id', $request->country)
                  ->orWhere('name', 'like', '%' . $request->country . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $accommodations = $query->paginate(12);

        return view('accommodations.index', compact('accommodations'));
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
        ]);

        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if (empty($data['currency'])) {
            $data['currency'] = 'USD';
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        $data['amenities'] = $request->filled('amenities_list')
            ? $request->input('amenities_list')
            : null;

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
        ]);

        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if (empty($data['currency'])) {
            $data['currency'] = $accommodation->currency ?? 'USD';
        }

        if (empty($accommodation->slug)) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        }

        $data['amenities'] = $request->filled('amenities_list')
            ? $request->input('amenities_list')
            : null;

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

        // DELETE SELECTED GALLERY IMAGES
        try {
            if ($request->filled('delete_images')) {
                $ids = $request->input('delete_images', []);
                Log::info('Deleting gallery images in adminUpdate', [
                    'accommodation_id' => $accommodation->id,
                    'image_ids'        => $ids,
                ]);

                $imagesToDelete = $accommodation->images()->whereIn('id', $ids)->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error deleting gallery images in adminUpdate', [
                'accommodation_id' => $accommodation->id,
                'message'          => $e->getMessage(),
                'trace'            => $e->getTraceAsString(),
            ]);
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
 * Used in tour create/edit forms for searchable dropdown
 */
public function apiSearch(Request $request)
{
    $query = Accommodation::query()->where('is_active', true);

    // Search by name
    if ($request->filled('q')) {
        $searchTerm = $request->input('q');
        $query->where('name', 'like', "%{$searchTerm}%");
    }

    // Filter by type
    if ($request->filled('type')) {
        $query->where('type', $request->input('type'));
    }

    // Filter by category
    if ($request->filled('category')) {
        $query->where('category', $request->input('category'));
    }

    // Filter by destination
    if ($request->filled('destination_id')) {
        $query->where('destination_id', $request->input('destination_id'));
    }

    // Filter by country
    if ($request->filled('country_id')) {
        $query->where('country_id', $request->input('country_id'));
    }

    // Load images relationship
    $accommodations = $query->with('images')
                           ->orderBy('name')
                           ->limit(50) // Limit results for performance
                           ->get();

    // Return JSON response with accommodation data and images
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
 * Used when loading edit form with existing accommodation selected
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
        
}