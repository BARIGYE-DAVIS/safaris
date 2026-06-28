<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Country;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class DestinationController extends Controller
{
    /**
     * ADMIN: Display a listing of destinations
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = Destination::with('country');

            if ($request->filled('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            if ($request->filled('popular')) {
                $query->where('is_popular', $request->popular === 'yes');
            }

            $destinations = $query->ordered()->paginate(15);
            $countries = Country::active()->ordered()->get();

            return view('admin.destinations.index', compact('destinations', 'countries'));

        } catch (Exception $e) {
            Log::error('Error in adminIndex', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error loading destinations: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Show the form for creating a new destination
     */
    public function adminCreate()
    {
        try {
            $countries = Country::active()->ordered()->get();
            return view('admin.destinations.create', compact('countries'));
        } catch (Exception $e) {
            Log::error('Error in adminCreate', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error loading create form: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Store a newly created destination
     */
    public function adminStore(Request $request)
    {
        try {
            Log::info('Creating destination', ['request_data' => $request->except(['image', 'featured_image'])]);

            $validated = $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:destinations,slug',
                'type' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'focus_keyword' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'area_size' => 'nullable|integer|min:0',
                'altitude_min' => 'nullable|integer',
                'altitude_max' => 'nullable|integer',
                'entry_fee_foreign' => 'nullable|numeric|min:0',
                'entry_fee_resident' => 'nullable|numeric|min:0',
                'entry_fee_local' => 'nullable|numeric|min:0',
                'currency' => 'nullable|string|max:10',
                'established_year' => 'nullable|digits:4',
                'annual_visitors' => 'nullable|integer|min:0',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'opening_hours' => 'nullable|string|max:255',
                'best_season' => 'nullable|string|max:255',
                'climate' => 'nullable|string|max:255',
                'avg_temp_high' => 'nullable|integer',
                'avg_temp_low' => 'nullable|integer',
                'rainfall_annual' => 'nullable|integer',
                'is_popular' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string',
                'status' => 'nullable|in:draft,published',
            ]);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if ($request->filled('status') && $request->status === 'published') {
                $validated['is_active'] = true;
                $validated['published_at'] = now();
                $validated['is_draft'] = false;
            } else {
                $validated['is_active'] = false;
                $validated['is_draft'] = true;
                $validated['draft_user_id'] = Auth::id();
            }

            $validated['is_popular'] = $request->has('is_popular');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            DB::beginTransaction();

            // Create destination
            $destination = Destination::create($validated);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $path = $this->storeImage($request->file('featured_image'), 'destinations/featured');
                $destination->update(['featured_image' => $path]);
                Log::info('Featured image uploaded', ['path' => $path]);
            }

            // Handle main image
            if ($request->hasFile('image')) {
                $path = $this->storeImage($request->file('image'), 'destinations');
                $destination->update(['image' => $path]);
                Log::info('Main image uploaded', ['path' => $path]);
            }

            // Process blocks - ONLY from blocks array
            // saveBlocks() creates DestinationImage rows for image blocks AND
            // returns the cleaned block structure (no raw UploadedFile objects)
            // so it can be persisted to destinations.content_blocks for rendering.
            if ($request->has('blocks') && is_array($request->blocks)) {
                $contentBlocks = $this->saveBlocks($destination->id, $request->blocks);
                $destination->update(['content_blocks' => $contentBlocks]);
            }

            DB::commit();

            Log::info('Destination created successfully', ['destination_id' => $destination->id]);

            return redirect()->route('admin.destinations.index')
                            ->with('success', 'Destination created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in adminStore', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating destination', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Error creating destination: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ADMIN: Show the form for editing the specified destination
     */
    public function adminEdit(Destination $destination)
    {
        try {
            $countries = Country::active()->ordered()->get();
            $destination->load('destinationImages');
            return view('admin.destinations.edit', compact('destination', 'countries'));
        } catch (Exception $e) {
            Log::error('Error in adminEdit', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Update the specified destination
     */
    public function adminUpdate(Request $request, Destination $destination)
    {
        try {
            Log::info('Updating destination', ['destination_id' => $destination->id]);

            $validated = $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:destinations,slug,' . $destination->id,
                'type' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'focus_keyword' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'area_size' => 'nullable|integer|min:0',
                'altitude_min' => 'nullable|integer',
                'altitude_max' => 'nullable|integer',
                'entry_fee_foreign' => 'nullable|numeric|min:0',
                'entry_fee_resident' => 'nullable|numeric|min:0',
                'entry_fee_local' => 'nullable|numeric|min:0',
                'currency' => 'nullable|string|max:10',
                'established_year' => 'nullable|digits:4',
                'annual_visitors' => 'nullable|integer|min:0',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'opening_hours' => 'nullable|string|max:255',
                'best_season' => 'nullable|string|max:255',
                'climate' => 'nullable|string|max:255',
                'avg_temp_high' => 'nullable|integer',
                'avg_temp_low' => 'nullable|integer',
                'rainfall_annual' => 'nullable|integer',
                'is_popular' => 'nullable|boolean',
                'sort_order' => 'nullable|integer|min:0',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:320',
                'meta_keywords' => 'nullable|string',
                'status' => 'nullable|in:draft,published',
            ]);

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            if ($request->filled('status') && $request->status === 'published') {
                $validated['is_active'] = true;
                $validated['is_draft'] = false;
                $validated['published_at'] = now();
            } else {
                $validated['is_active'] = false;
                $validated['is_draft'] = true;
                $validated['draft_user_id'] = Auth::id();
            }

            $validated['is_popular'] = $request->has('is_popular');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            DB::beginTransaction();

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                if ($destination->featured_image) {
                    $this->deleteImage($destination->featured_image);
                }
                $path = $this->storeImage($request->file('featured_image'), 'destinations/featured');
                $validated['featured_image'] = $path;
                Log::info('Featured image uploaded', ['path' => $path]);
            }

            // Handle main image
            if ($request->hasFile('image')) {
                if ($destination->image) {
                    $this->deleteImage($destination->image);
                }
                $path = $this->storeImage($request->file('image'), 'destinations');
                $validated['image'] = $path;
                Log::info('Main image uploaded', ['path' => $path]);
            }

            // Update destination
            $destination->update($validated);

            // Sync blocks - syncBlocks() handles image diffing AND returns the
            // cleaned block structure to persist to destinations.content_blocks
            if ($request->has('blocks') && is_array($request->blocks)) {
                $contentBlocks = $this->syncBlocks($destination, $request->blocks);
                $destination->update(['content_blocks' => $contentBlocks]);
            }

            DB::commit();

            Log::info('Destination updated successfully', ['destination_id' => $destination->id]);

            return redirect()->route('admin.destinations.edit', $destination->id)
                            ->with('success', 'Destination updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in adminUpdate', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating destination', [
                'destination_id' => $destination->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error updating destination: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ADMIN: Remove the specified destination
     */
    public function adminDestroy(Destination $destination)
    {
        try {
            if ($destination->featured_image) {
                $this->deleteImage($destination->featured_image);
            }
            if ($destination->image) {
                $this->deleteImage($destination->image);
            }

            foreach ($destination->destinationImages as $img) {
                $this->deleteImage($img->storage_path);
                $img->delete();
            }

            $destination->delete();

            Log::info('Destination deleted successfully', ['destination_id' => $destination->id]);

            return redirect()->route('admin.destinations.index')
                            ->with('success', 'Destination deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting destination', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error deleting destination: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Toggle destination active status
     */
    public function adminToggleStatus(Destination $destination)
    {
        try {
            $destination->update(['is_active' => !$destination->is_active]);
            $status = $destination->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Destination {$status} successfully!");
        } catch (Exception $e) {
            Log::error('Error toggling destination status', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error toggling status: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Toggle popular status
     */
    public function adminTogglePopular(Destination $destination)
    {
        try {
            $destination->update(['is_popular' => !$destination->is_popular]);
            $status = $destination->is_popular ? 'marked as popular' : 'unmarked as popular';
            return back()->with('success', "Destination {$status} successfully!");
        } catch (Exception $e) {
            Log::error('Error toggling popular status', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error toggling popular: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Bulk delete destinations
     */
    public function adminBulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:destinations,id'
            ]);

            $destinations = Destination::whereIn('id', $request->ids)->get();

            foreach ($destinations as $destination) {
                if ($destination->featured_image) {
                    $this->deleteImage($destination->featured_image);
                }
                if ($destination->image) {
                    $this->deleteImage($destination->image);
                }

                foreach ($destination->destinationImages as $img) {
                    $this->deleteImage($img->storage_path);
                    $img->delete();
                }

                $destination->delete();
            }

            return back()->with('success', 'Selected destinations deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error in bulk delete', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error deleting destinations: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Update sort order
     */
    public function adminUpdateOrder(Request $request)
    {
        try {
            $request->validate([
                'orders' => 'required|array',
                'orders.*.id' => 'required|exists:destinations,id',
                'orders.*.sort_order' => 'required|integer',
            ]);

            foreach ($request->orders as $order) {
                Destination::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            return response()->json(['success' => true, 'message' => 'Order updated successfully!']);

        } catch (Exception $e) {
            Log::error('Error updating order', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error updating order']);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE METHODS
    // ─────────────────────────────────────────────────────────────

    /**
     * Save blocks.
     * Creates DestinationImage rows for any image blocks (via syncBlockImages),
     * and returns the cleaned block structure — safe to JSON-encode and persist
     * to destinations.content_blocks — which renderContentBlocks() reads later.
     */
    private function saveBlocks(int $destinationId, array $blocksData): array
    {
        $contentBlocks = [];
        $sortOrder = 0;

        foreach ($blocksData as $blockData) {
            $contentBlocks[] = $this->createBlock($destinationId, $blockData, $sortOrder);
            $sortOrder++;
        }

        return $contentBlocks;
    }


    /**
 * Create block.
 * For image blocks: ensures a stable block_id, saves the uploaded files
 * to DestinationImage, and returns a clean (JSON-safe) block array.
 * For other block types: returns the block data as-is.
 * Updated to support 'table' and 'buttons' block types with colors.
 */
private function createBlock(int $destinationId, array $blockData, int $sortOrder): array
{
    $type = $blockData['type'] ?? 'text';

    // --- IMAGE BLOCK ---
    if ($type === 'image') {
        $blockId = $blockData['id'] ?? 'blk-' . Str::random(8);
        $blockData['id'] = $blockId;

        $this->syncBlockImages($destinationId, $blockData, $sortOrder);

        return [
            'type' => 'image',
            'id' => $blockId,
            'caption' => $blockData['caption'] ?? null,
        ];
    }

    // --- TABLE BLOCK with colors ---
    if ($type === 'table') {
        return [
            'type' => 'table',
            'id' => $blockData['id'] ?? 'tbl-' . Str::random(8),
            'headers' => $blockData['headers'] ?? [],
            'rows' => $blockData['rows'] ?? [],
            'caption' => $blockData['caption'] ?? null,
            // Table styling
            'striped' => isset($blockData['striped']),
            'bordered' => isset($blockData['bordered']) ? true : false,
            'hoverable' => isset($blockData['hoverable']),
            'small' => isset($blockData['small']),
            // Table colors
            'header_bg_color' => $blockData['header_bg_color'] ?? '#f3f4f6',  // gray-100 default
            'header_text_color' => $blockData['header_text_color'] ?? '#111827', // gray-900 default
            'row_bg_color' => $blockData['row_bg_color'] ?? '#ffffff',
            'row_bg_alt_color' => $blockData['row_bg_alt_color'] ?? '#f9fafb', // gray-50 default
            'row_text_color' => $blockData['row_text_color'] ?? '#111827',
            'border_color' => $blockData['border_color'] ?? '#d1d5db', // gray-300 default
        ];
    }

    // --- BUTTONS BLOCK with colors ---
    if ($type === 'buttons') {
        // Process each button with its own colors
        $buttons = [];
        if (!empty($blockData['buttons']) && is_array($blockData['buttons'])) {
            foreach ($blockData['buttons'] as $btn) {
                $buttons[] = [
                    'text' => $btn['text'] ?? 'Button',
                    'url' => $btn['url'] ?? '#',
                    'bg_color' => $btn['bg_color'] ?? '#2563eb', // blue-600 default
                    'text_color' => $btn['text_color'] ?? '#ffffff',
                    'hover_bg_color' => $btn['hover_bg_color'] ?? '#1d4ed8', // blue-700 default
                    'hover_text_color' => $btn['hover_text_color'] ?? '#ffffff',
                    'border_color' => $btn['border_color'] ?? '#2563eb',
                    'border_radius' => $btn['border_radius'] ?? '8px',
                    'size' => $btn['size'] ?? 'medium', // small, medium, large
                    'type' => $btn['type'] ?? 'primary', // primary, secondary, outline, ghost
                    'icon' => $btn['icon'] ?? null,
                    'target' => $btn['target'] ?? '_self',
                    'rel' => $btn['rel'] ?? '',
                ];
            }
        }

        return [
            'type' => 'buttons',
            'id' => $blockData['id'] ?? 'btn-' . Str::random(8),
            'buttons' => $buttons,
            'alignment' => $blockData['alignment'] ?? 'left',
            'gap' => $blockData['gap'] ?? 'medium',
            'direction' => $blockData['direction'] ?? 'horizontal',
            // Global button styles (can be overridden per button)
            'default_bg_color' => $blockData['default_bg_color'] ?? '#2563eb',
            'default_text_color' => $blockData['default_text_color'] ?? '#ffffff',
            'default_hover_bg_color' => $blockData['default_hover_bg_color'] ?? '#1d4ed8',
            'default_hover_text_color' => $blockData['default_hover_text_color'] ?? '#ffffff',
            'default_border_radius' => $blockData['default_border_radius'] ?? '8px',
        ];
    }

    // --- TEXT, HEADING, LIST blocks ---
    return $blockData;
}

    /**
     * Sync block images
     * NOTE: No getSize() / getClientSize() call anywhere in this method.
     * No 'size_bytes' is written — if that DB column is NOT NULL, either
     * make it nullable or drop it (see migration note below).
     */
    private function syncBlockImages(int $destinationId, array $blockData, int $sortOrder): void
    {
        $newImages = $blockData['images'] ?? [];
        $altTexts = $blockData['alts'] ?? [];
        $blockId = $blockData['id'] ?? 'blk-' . Str::random(8);

        if (!empty($newImages) && is_array($newImages)) {
            foreach ($newImages as $i => $newImage) {
                if ($newImage && $newImage instanceof UploadedFile && $newImage->isValid()) {
                    try {
                        $mime = $newImage->getClientMimeType();
                        $path = $this->storeImage($newImage, 'destinations/blocks');
                        $altText = $altTexts[$i] ?? null;

                        DestinationImage::create([
                            'destination_id' => $destinationId,
                            'block_id' => $blockId,
                            'storage_path' => $path,
                            'thumbnail_path' => null,
                            'caption' => $blockData['caption'] ?? null,
                            'alt_text' => $altText,
                            'mime_type' => $mime,
                            'order' => $i,
                            'uploaded_by' => Auth::id(),
                        ]);

                        Log::info('Image saved successfully', [
                            'block_id' => $blockId,
                            'path' => $path,
                            'destination_id' => $destinationId
                        ]);

                    } catch (Exception $e) {
                        Log::error('Error saving image', [
                            'error' => $e->getMessage(),
                            'image_index' => $i
                        ]);
                        continue;
                    }
                }
            }
        }
    }

    /**
     * Sync blocks (update path).
     * Diffs image blocks against existing DestinationImage rows, and returns
     * the cleaned block structure to persist to destinations.content_blocks.
     */
private function syncBlocks(Destination $destination, array $blocksData): array
{
    $existingBlockIds = $destination->destinationImages->pluck('block_id')->unique()->toArray();
    $processedBlockIds = [];
    $contentBlocks = [];
    $sortOrder = 0;

    foreach ($blocksData as $blockData) {
        $type = $blockData['type'] ?? 'text';
        $blockId = $blockData['id'] ?? null;

        // --- IMAGE BLOCK ---
        if ($type === 'image') {
            $blockId = $blockId ?? 'blk-' . Str::random(8);
            $blockData['id'] = $blockId;

            // Delete flagged images
            $deleteIds = array_filter($blockData['delete_images'] ?? []);
            if (!empty($deleteIds)) {
                $toDelete = $destination->destinationImages
                    ->where('block_id', $blockId)
                    ->whereIn('id', $deleteIds);
                foreach ($toDelete as $img) {
                    $this->deleteImage($img->storage_path);
                    $img->delete();
                }
            }

            // Update alt text for existing images
            $existingAlts = $blockData['existing_alts'] ?? [];
            foreach ($existingAlts as $imageId => $altText) {
                DestinationImage::find($imageId)?->update(['alt_text' => $altText]);
            }

            // Append new images
            $this->syncBlockImages($destination->id, $blockData, $sortOrder);
            $processedBlockIds[] = $blockId;

            $contentBlocks[] = [
                'type'    => 'image',
                'id'      => $blockId,
                'caption' => $blockData['caption'] ?? null,
            ];
        }

        // --- TABLE BLOCK with colors ---
        elseif ($type === 'table') {
            $blockId = $blockId ?? 'tbl-' . Str::random(8);
            $contentBlocks[] = [
                'type' => 'table',
                'id' => $blockId,
                'headers' => $blockData['headers'] ?? [],
                'rows' => $blockData['rows'] ?? [],
                'caption' => $blockData['caption'] ?? null,
                // Table styling
                'striped' => isset($blockData['striped']),
                'bordered' => isset($blockData['bordered']) ? true : false,
                'hoverable' => isset($blockData['hoverable']),
                'small' => isset($blockData['small']),
                // Table colors
                'header_bg_color' => $blockData['header_bg_color'] ?? '#f3f4f6',
                'header_text_color' => $blockData['header_text_color'] ?? '#111827',
                'row_bg_color' => $blockData['row_bg_color'] ?? '#ffffff',
                'row_bg_alt_color' => $blockData['row_bg_alt_color'] ?? '#f9fafb',
                'row_text_color' => $blockData['row_text_color'] ?? '#111827',
                'border_color' => $blockData['border_color'] ?? '#d1d5db',
            ];
            $processedBlockIds[] = $blockId;
        }

        // --- BUTTONS BLOCK with colors ---
        elseif ($type === 'buttons') {
            $blockId = $blockId ?? 'btn-' . Str::random(8);
            
            // Process buttons with colors
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
                        'border_color' => $btn['border_color'] ?? '#2563eb',
                        'border_radius' => $btn['border_radius'] ?? '8px',
                        'size' => $btn['size'] ?? 'medium',
                        'type' => $btn['type'] ?? 'primary',
                        'icon' => $btn['icon'] ?? null,
                        'target' => $btn['target'] ?? '_self',
                        'rel' => $btn['rel'] ?? '',
                    ];
                }
            }

            $contentBlocks[] = [
                'type' => 'buttons',
                'id' => $blockId,
                'buttons' => $buttons,
                'alignment' => $blockData['alignment'] ?? 'left',
                'gap' => $blockData['gap'] ?? 'medium',
                'direction' => $blockData['direction'] ?? 'horizontal',
                'default_bg_color' => $blockData['default_bg_color'] ?? '#2563eb',
                'default_text_color' => $blockData['default_text_color'] ?? '#ffffff',
                'default_hover_bg_color' => $blockData['default_hover_bg_color'] ?? '#1d4ed8',
                'default_hover_text_color' => $blockData['default_hover_text_color'] ?? '#ffffff',
                'default_border_radius' => $blockData['default_border_radius'] ?? '8px',
            ];
            $processedBlockIds[] = $blockId;
        }

        // --- OTHER BLOCKS (text, heading, list) ---
        else {
            $contentBlocks[] = $blockData;
        }

        $sortOrder++;
    }

    // Delete image blocks that were entirely removed
    foreach ($existingBlockIds as $blockId) {
        if (!in_array($blockId, $processedBlockIds)) {
            $images = $destination->destinationImages->where('block_id', $blockId);
            foreach ($images as $image) {
                $this->deleteImage($image->storage_path);
                $image->delete();
            }
        }
    }

    return $contentBlocks;
}
    /**
     * Store image
     */
    private function storeImage(UploadedFile $file, string $folder): string
    {
        $destination = public_path('storage/' . $folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::random(40) . '.' . $extension;
        $file->move($destination, $filename);

        return $folder . '/' . $filename;
    }

    /**
     * Delete image
     */
    private function deleteImage(string $relativePath): void
    {
        $fullPath = public_path('storage/' . $relativePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // PUBLIC METHODS
    // ─────────────────────────────────────────────────────────────

    /**
     * PUBLIC: Display list of destinations
     */
    public function index(Request $request)
    {
        try {
            $query = Destination::with('country')->where('is_active', true);

            if ($request->filled('country')) {
                $query->whereHas('country', function ($q) use ($request) {
                    $q->where('code', $request->country);
                });
            }

            if ($request->filled('popular')) {
                $query->where('is_popular', true);
            }

            if ($request->filled('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            $destinations = $query->orderBy('sort_order')->orderBy('name')->paginate(12);
            $countries = Country::where('is_active', true)->orderBy('name')->get();

            $popularDestinations = Destination::where('is_active', true)
                                              ->where('is_popular', true)
                                              ->orderBy('sort_order')
                                              ->limit(6)
                                              ->get();

            return view('destinations.index', compact('destinations', 'countries', 'popularDestinations'));

        } catch (Exception $e) {
            Log::error('Error in public index', ['message' => $e->getMessage()]);
            abort(500, 'Error loading destinations');
        }
    }

    /**
     * PUBLIC: Show single destination
     */
    public function show($slug)
    {
        try {
            $destination = Destination::where('slug', $slug)
                                      ->with('country', 'destinationImages')
                                      ->where('is_active', true)
                                      ->firstOrFail();

            $relatedDestinations = Destination::where('country_id', $destination->country_id)
                                             ->where('id', '!=', $destination->id)
                                             ->where('is_active', true)
                                             ->ordered()
                                             ->limit(3)
                                             ->get();

            return view('destinations.show', compact('destination', 'relatedDestinations'));

        } catch (Exception $e) {
            Log::error('Error showing destination', ['slug' => $slug, 'message' => $e->getMessage()]);
            abort(404);
        }
    }

    /**
     * API: Get destinations by country
     */
    public function getByCountry($countryId)
    {
        try {
            $destinations = Destination::where('country_id', $countryId)
                                       ->where('is_active', true)
                                       ->ordered()
                                       ->get();
            return response()->json($destinations);

        } catch (Exception $e) {
            Log::error('Error getting destinations by country', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Error loading destinations'], 500);
        }
    }

    /**
     * API: Get popular destinations
     */
    public function getPopular()
    {
        try {
            $destinations = Destination::where('is_active', true)
                                       ->where('is_popular', true)
                                       ->ordered()
                                       ->limit(6)
                                       ->get();
            return response()->json($destinations);

        } catch (Exception $e) {
            Log::error('Error getting popular destinations', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Error loading destinations'], 500);
        }
    }
}