<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourItinerary;
use App\Models\TourPrice;
use App\Models\TourImage;
use App\Models\Accommodation;
use App\Models\TourItineraryImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminTourController extends Controller
{
    public function index()
    {
        $tours = Tour::latest()->paginate(10);
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        // Get active accommodations for the dropdown/search
        $accommodations = Accommodation::where('is_active', true)
                                       ->orderBy('name')
                                       ->get(['id', 'name', 'type', 'category', 'location']);
        
        return view('admin.tours.create', compact('accommodations'));
    }

    /**
     * Create a new tour and process itinerary content_blocks + uploads.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'category'          => 'required|string|max:255',
            'destinations'      => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'description'       => 'required|string',
            'included'          => 'nullable|string',
            'excluded'          => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // itinerary shape: fields plus a content_blocks hidden JSON and uploads keyed by temp id
            'itinerary'                     => 'nullable|array',
            'itinerary.*.day_number'        => 'nullable|integer',
            'itinerary.*.day_title'         => 'nullable|string',
            'itinerary.*.activity'          => 'nullable|string',
            'itinerary.*.accommodation'     => 'nullable|string',
            'itinerary.*.accommodation_id'  => 'nullable|integer|exists:accommodations,id',
            'itinerary.*.meals'             => 'nullable|string',
            'itinerary.*.content_blocks'    => 'nullable|string', // JSON string built by frontend
            // uploads are validated per-file below (non-standard keys), validate loose overall:
            'itinerary.*.uploads'           => 'nullable|array',
            // prices and tour-level images (existing behavior)
            'prices'                        => 'nullable|array',
            'images'                        => 'nullable',
        ]);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('tours/featured_images', 'public');
            $validated['featured_image'] = $path;
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Wrap in transaction to avoid partially created state
        DB::beginTransaction();
        try {
            $tour = Tour::create($validated);

            // Itinerary days (array input) — handle content_blocks & images per-day
            if ($request->has('itinerary')) {
                foreach ($request->input('itinerary') as $dayKey => $day) {
                    // Create itinerary row
                    // Use relationship name the app expects (some projects use itinerary / itineraries)
                    $it = $tour->itinerary()->create([
                        'day_number'        => $day['day_number'] ?? null,
                        'activity'          => $day['activity'] ?? '',
                        'day_title'         => $day['day_title'] ?? '',
                        'accommodation'     => $day['accommodation'] ?? '',
                        // FIX: cast to int — empty string '' from form must become null, not ''
                        'accommodation_id'  => !empty($day['accommodation_id']) ? (int) $day['accommodation_id'] : null,
                        'meals'             => $day['meals'] ?? '',
                        // content_blocks will be set below after processing uploads
                    ]);

                    // Process content_blocks for this day (frontend sends JSON string in itinerary[<key>][content_blocks])
                    $contentBlocksJson = $request->input("itinerary.$dayKey.content_blocks");
                    $blocks = [];
                    if (!empty($contentBlocksJson)) {
                        $blocks = json_decode($contentBlocksJson, true);
                        if (!is_array($blocks)) $blocks = [];
                    }

                    // Build a map of uploads submitted for this day: $request->file("itinerary.$dayKey.uploads")
                    $uploadsForDay = $request->file("itinerary.$dayKey.uploads") ?? [];

                    // Iterate blocks and handle image blocks with temp_media_id
                    foreach ($blocks as $bIndex => $block) {
                        if (!isset($block['type'])) continue;

                        if ($block['type'] === 'image') {
                            // Use provided block id (frontend should set it)
                            $blockId = $block['id'] ?? ('blk-' . (string) Str::uuid());

                            // Case A: frontend provided a temp_media_id => expect an uploaded file keyed by that temp id
                            if (!empty($block['temp_media_id'])) {
                                $tmpId = $block['temp_media_id'];

                                // Laravel can retrieve nested file by "itinerary.1.uploads.tmp-xxxx" using array-style
                                $file = $uploadsForDay[$tmpId] ?? null;

                                if ($file && $file->isValid()) {
                                    $path = $file->store("tours/{$tour->id}/itineraries/{$it->id}", 'public');

                                    // Determine next order number
                                    $maxOrder = $it->images()->max('order') ?? 0;

                                    $image = $it->images()->create([
                                        'block_id'       => $blockId,
                                        'storage_path'   => $path,
                                        'thumbnail_path' => null,
                                        'caption'        => $block['caption'] ?? null,
                                        'alt_text'       => $block['caption'] ?? null,
                                        'mime_type'      => $file->getClientMimeType(),
                                        'size_bytes'     => $file->getSize(),
                                        'order'          => $maxOrder + 1,
                                        'uploaded_by'    => Auth::id() ?? null,
                                    ]);

                                    // Replace temp id in the block with persistent media id
                                    $blocks[$bIndex]['media_id'] = $image->id;
                                    unset($blocks[$bIndex]['temp_media_id']);
                                } else {
                                    // No file uploaded for this temp id — leave block as-is (frontend might not have attached file)
                                }
                            }

                            // Case B: frontend may include media_id for existing media (rare on create) — ensure block_id is set on row later if needed
                            elseif (!empty($block['media_id'])) {
                                $mediaId = $block['media_id'];
                                $existingImage = TourItineraryImage::find($mediaId);
                                if ($existingImage && $existingImage->tour_itinerary_id == $it->id) {
                                    // ensure block_id persisted
                                    if (empty($existingImage->block_id)) {
                                        $existingImage->block_id = $blockId;
                                        $existingImage->save();
                                    }
                                    $blocks[$bIndex]['media_id'] = $existingImage->id;
                                } else {
                                    // media_id points elsewhere; ignore or remove
                                    unset($blocks[$bIndex]['media_id']);
                                }
                            } else {
                                // No media reference — nothing to do
                            }

                            // Ensure block has id
                            $blocks[$bIndex]['id'] = $blockId;
                        }
                    }

                    // Save updated content_blocks JSON on itinerary
                    $it->content_blocks = $blocks;
                    $it->save();

                    // Optionally: set cover_media_id if any block has is_cover true
                    foreach ($blocks as $blk) {
                        if (isset($blk['type']) && $blk['type'] === 'image' && !empty($blk['is_cover']) && !empty($blk['media_id'])) {
                            $it->cover_media_id = $blk['media_id'];
                            $it->cover_caption = $blk['caption'] ?? null;
                            $it->save();
                            break;
                        }
                    }
                }
            }

            // Prices
            if ($request->has('prices')) {
                foreach ($request->input('prices') as $price) {
                    $tour->prices()->create([
                        'group_size' => $price['group_size'] ?? 1,
                        'price'      => $price['price'] ?? 0,
                    ]);
                }
            }

            // Tour-level Images (existing behavior)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imgPath = $img->store('tours/images', 'public');
                    $tour->images()->create([
                        'image_path' => $imgPath,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour, itinerary, prices, and images created!');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit($id)
    {
        // FIX: relationship is named 'accommodationRecord' on TourItinerary model.
        // We cannot use 'accommodation' because that is also a plain text column —
        // Eloquent would return the relation proxy instead of the string value everywhere.
        $tour = Tour::with([
            'itinerary.accommodationRecord.images',
            'itinerary.images',
            'prices',
            'images',
        ])->findOrFail($id);
        
        // Get active accommodations for the dropdown/search
        $accommodations = Accommodation::where('is_active', true)
                                       ->orderBy('name')
                                       ->get(['id', 'name', 'type', 'category', 'location']);
        
        return view('admin.tours.edit', compact('tour', 'accommodations'));
    }

    /**
     * Update tour, process itinerary content_blocks, map uploads to media rows, and handle deletions.
     */
    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'category'          => 'required|string|max:255',
            'destinations'      => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'description'       => 'required|string',
            'included'          => 'nullable|string',
            'excluded'          => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'featured_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'itinerary'                     => 'nullable|array',
            'itinerary.*.day_number'        => 'nullable|integer',
            'itinerary.*.day_title'         => 'nullable|string',
            'itinerary.*.activity'          => 'nullable|string',
            'itinerary.*.accommodation'     => 'nullable|string',
            'itinerary.*.accommodation_id'  => 'nullable|integer|exists:accommodations,id',
            'itinerary.*.meals'             => 'nullable|string',
            'itinerary.*.content_blocks'    => 'nullable|string',
            'itinerary.*.uploads'           => 'nullable|array',
            'delete_itinerary_image_ids'    => 'nullable|array',
            'delete_itinerary_image_ids.*'  => 'integer',
        ]);

        // Handle featured image update
        if ($request->hasFile('featured_image')) {
            // Delete old featured image if exists
            if ($tour->featured_image) {
                Storage::disk('public')->delete($tour->featured_image);
            }
            $path = $request->file('featured_image')->store('tours/featured_images', 'public');
            $validated['featured_image'] = $path;
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        DB::beginTransaction();
        try {
            $tour->update($validated);

            // ============================================
            // UPDATE ITINERARIES (create & update rows)
            // ============================================
            if ($request->has('itinerary')) {
                $submittedItineraryIds = [];

                foreach ($request->input('itinerary') as $dayKey => $dayData) {
                    // FIX: cast to int — empty string '' from the hidden input must become null
                    $accommodationId = !empty($dayData['accommodation_id'])
                        ? (int) $dayData['accommodation_id']
                        : null;

                    // if id present -> update; else create new
                    if (isset($dayData['id']) && $dayData['id']) {
                        $itinerary = TourItinerary::where('tour_id', $tour->id)
                            ->where('id', $dayData['id'])
                            ->first();

                        if ($itinerary) {
                            $itinerary->update([
                                'day_number'        => $dayData['day_number'] ?? null,
                                'activity'          => $dayData['activity'] ?? '',
                                'day_title'         => $dayData['day_title'] ?? '',
                                'accommodation'     => $dayData['accommodation'] ?? '',
                                'accommodation_id'  => $accommodationId,
                                'meals'             => $dayData['meals'] ?? '',
                            ]);
                            $submittedItineraryIds[] = $itinerary->id;
                        } else {
                            // fallback: create new if not found
                            $itinerary = $tour->itinerary()->create([
                                'day_number'        => $dayData['day_number'] ?? null,
                                'activity'          => $dayData['activity'] ?? '',
                                'day_title'         => $dayData['day_title'] ?? '',
                                'accommodation'     => $dayData['accommodation'] ?? '',
                                'accommodation_id'  => $accommodationId,
                                'meals'             => $dayData['meals'] ?? '',
                            ]);
                            $submittedItineraryIds[] = $itinerary->id;
                        }
                    } else {
                        // Create new itinerary
                        $itinerary = $tour->itinerary()->create([
                            'day_number'        => $dayData['day_number'] ?? null,
                            'activity'          => $dayData['activity'] ?? '',
                            'day_title'         => $dayData['day_title'] ?? '',
                            'accommodation'     => $dayData['accommodation'] ?? '',
                            'accommodation_id'  => $accommodationId,
                            'meals'             => $dayData['meals'] ?? '',
                        ]);
                        $submittedItineraryIds[] = $itinerary->id;
                    }

                    // Process content_blocks for this day (map uploads and persist media rows)
                    $contentBlocksJson = $request->input("itinerary.$dayKey.content_blocks");
                    $blocks = [];
                    if (!empty($contentBlocksJson)) {
                        $blocks = json_decode($contentBlocksJson, true);
                        if (!is_array($blocks)) $blocks = [];
                    }

                    $uploadsForDay = $request->file("itinerary.$dayKey.uploads") ?? [];

                    foreach ($blocks as $bIndex => $block) {
                        if (!isset($block['type'])) continue;

                        if ($block['type'] === 'image') {
                            $blockId = $block['id'] ?? ('blk-' . (string) Str::uuid());

                            // New upload referenced via temp_media_id
                            if (!empty($block['temp_media_id'])) {
                                $tmpId = $block['temp_media_id'];
                                $file = $uploadsForDay[$tmpId] ?? null;

                                if ($file && $file->isValid()) {
                                    $path = $file->store("tours/{$tour->id}/itineraries/{$itinerary->id}", 'public');

                                    $maxOrder = $itinerary->images()->max('order') ?? 0;

                                    $image = $itinerary->images()->create([
                                        'block_id'       => $blockId,
                                        'storage_path'   => $path,
                                        'thumbnail_path' => null,
                                        'caption'        => $block['caption'] ?? null,
                                        'alt_text'       => $block['caption'] ?? null,
                                        'mime_type'      => $file->getClientMimeType(),
                                        'size_bytes'     => $file->getSize(),
                                        'order'          => $maxOrder + 1,
                                        'uploaded_by'    => Auth::id() ?? null,
                                    ]);

                                    $blocks[$bIndex]['media_id'] = $image->id;
                                    unset($blocks[$bIndex]['temp_media_id']);
                                }
                            }
                            // Existing media reference - ensure block_id persisted
                            elseif (!empty($block['media_id'])) {
                                $mediaId = $block['media_id'];
                                $existingImage = TourItineraryImage::find($mediaId);
                                if ($existingImage && $existingImage->itinerary && $existingImage->itinerary->tour_id == $tour->id) {
                                    if (empty($existingImage->block_id)) {
                                        $existingImage->block_id = $blockId;
                                        $existingImage->save();
                                    }
                                    $blocks[$bIndex]['media_id'] = $existingImage->id;
                                } else {
                                    // media id invalid for this itinerary/tour: remove reference
                                    unset($blocks[$bIndex]['media_id']);
                                }
                            }

                            // Ensure block has id
                            $blocks[$bIndex]['id'] = $blockId;
                        }
                    }

                    // Save updated content_blocks JSON on itinerary
                    $itinerary->content_blocks = $blocks;
                    $itinerary->save();

                    // Optionally set cover_media_id if block flagged
                    foreach ($blocks as $blk) {
                        if (isset($blk['type']) && $blk['type'] === 'image' && !empty($blk['is_cover']) && !empty($blk['media_id'])) {
                            $itinerary->cover_media_id = $blk['media_id'];
                            $itinerary->cover_caption = $blk['caption'] ?? null;
                            $itinerary->save();
                            break;
                        }
                    }
                }

                // Delete itineraries that were removed (not in submitted list)
                TourItinerary::where('tour_id', $tour->id)
                    ->whereNotIn('id', $submittedItineraryIds)
                    ->delete();
            } else {
                // If no itinerary submitted, delete all existing
                $tour->itinerary()->delete();
            }

            // Handle explicit deletion of itinerary images
            if ($request->filled('delete_itinerary_image_ids')) {
                $idsToDelete = $request->input('delete_itinerary_image_ids', []);
                $images = TourItineraryImage::whereIn('id', $idsToDelete)->get();

                foreach ($images as $img) {
                    // Ensure the image belongs to this tour (safety)
                    if ($img->itinerary && $img->itinerary->tour_id == $tour->id) {
                        // Delete file from storage
                        Storage::disk('public')->delete($img->storage_path);
                        // Remove DB record
                        $img->delete();
                    }
                }
            }

            // ============================================
            // UPDATE PRICES
            // ============================================
            if ($request->has('prices')) {
                $submittedPriceIds = [];

                foreach ($request->input('prices') as $priceData) {
                    if (isset($priceData['id']) && $priceData['id']) {
                        // Update existing price
                        $price = TourPrice::where('tour_id', $tour->id)
                            ->where('id', $priceData['id'])
                            ->first();

                        if ($price) {
                            $price->update([
                                'group_size' => $priceData['group_size'] ?? 1,
                                'price'      => $priceData['price'] ?? 0,
                            ]);
                            $submittedPriceIds[] = $price->id;
                        }
                    } else {
                        // Create new price
                        $newPrice = $tour->prices()->create([
                            'group_size' => $priceData['group_size'] ?? 1,
                            'price'      => $priceData['price'] ?? 0,
                        ]);
                        $submittedPriceIds[] = $newPrice->id;
                    }
                }

                // Delete prices that were removed (not in submitted list)
                TourPrice::where('tour_id', $tour->id)
                    ->whereNotIn('id', $submittedPriceIds)
                    ->delete();
            } else {
                // If no prices submitted, delete all existing
                $tour->prices()->delete();
            }

            // ============================================
            // UPDATE IMAGES (tour-level)
            // ============================================

            // Handle deleted tour-level images
            if ($request->has('delete_images')) {
                foreach ($request->input('delete_images') as $imageId) {
                    $image = TourImage::where('tour_id', $tour->id)
                        ->where('id', $imageId)
                        ->first();

                    if ($image) {
                        // Delete the actual file
                        Storage::disk('public')->delete($image->image_path);
                        // Delete the database record
                        $image->delete();
                    }
                }
            }

            // Handle new tour-level images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    if ($img->isValid()) {
                        $imgPath = $img->store('tours/images', 'public');
                        $tour->images()->create([
                            'image_path' => $imgPath,
                            'uploaded_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.tours.edit', $tour->id)
                ->with('success', 'Tour and all related data updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);

        // Delete featured image
        if ($tour->featured_image) {
            Storage::disk('public')->delete($tour->featured_image);
        }

        // Delete all gallery images
        foreach ($tour->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete all itinerary images
        foreach ($tour->itinerary as $it) {
            foreach ($it->images as $img) {
                Storage::disk('public')->delete($img->storage_path);
            }
        }

        // Delete related records (if you have cascade delete in DB, this is optional)
        $tour->itinerary()->delete();
        $tour->prices()->delete();
        $tour->images()->delete();

        // Delete the tour
        $tour->delete();

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour and all related records deleted!');
    }
}