<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Country;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    /**
     * ADMIN: Display a listing of destinations
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = Destination::with('country')->withCount('activities');

            // Search
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%")
                      ->orWhere('region', 'like', "%{$request->search}%");
                });
            }

            // Filter by country
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            // Filter by popular
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
        Log::info('Creating destination (store)', ['request_data' => $request->except(['image','featured_image'])]);

        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug',
            'region' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'detailed_overview' => 'nullable|string',
            'what_to_see_do' => 'nullable|string',
            'wildlife_highlights' => 'nullable|string',
            'geography_landscape' => 'nullable|string',
            'best_time_visit' => 'nullable|string',
            'how_to_get_there' => 'nullable|string',
            'accommodation_options' => 'nullable|string',
            'practical_information' => 'nullable|string',
            'cultural_significance' => 'nullable|string',
            'photography_tips' => 'nullable|string',
            'nearby_attractions' => 'nullable|string',
            'interesting_facts' => 'nullable|string',

            // top-level images
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            // geography
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area_size' => 'nullable|integer|min:0',
            'altitude_min' => 'nullable|integer',
            'altitude_max' => 'nullable|integer',

            // pricing
            'entry_fee_foreign' => 'nullable|numeric|min:0',
            'entry_fee_resident' => 'nullable|numeric|min:0',
            'entry_fee_local' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',

            // other
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
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Top-level images (store paths to save with destination)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
            Log::info('Main image uploaded', ['path' => $validated['image']]);
        }
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('destinations/featured', 'public');
            Log::info('Featured image uploaded', ['path' => $validated['featured_image']]);
        }

        // Booleans & defaults
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Draft handling
        if ($request->filled('draft')) {
            $validated['is_draft'] = true;
            $validated['draft_user_id'] = Auth::id();
        } else {
            $validated['is_draft'] = false;
            $validated['published_at'] = now();
        }

        // Use DB transaction so we don't leave uploaded files referenced incorrectly on failure
        DB::beginTransaction();

        // Create destination first
        $destination = Destination::create($validated);

        // Process block-based sections and uploads
        $sectionsList = ['overview','activities','wildlife','geography','practical','accommodation','extras'];
        $sectionsContent = [];

        foreach ($sectionsList as $section) {
            $blocksJson = $request->input("sections.{$section}.content_blocks");
            if (!$blocksJson) {
                continue;
            }
            $blocks = json_decode($blocksJson, true) ?: [];
            $uploads = $request->file("sections.{$section}.uploads") ?: [];

            foreach ($blocks as $bi => $block) {
                if (($block['type'] ?? '') === 'image') {
                    // New upload keyed by temp id (tmp-...)
                    if (!empty($block['temp_media_id']) && isset($uploads[$block['temp_media_id']])) {
                        $file = $uploads[$block['temp_media_id']];
                        if ($file && $file->isValid()) {
                            $path = $file->store("destinations/{$section}", 'public');
                            $image = DestinationImage::create([
                                'destination_id' => $destination->id,
                                'block_id' => $block['id'] ?? null,
                                'storage_path' => $path,
                                'thumbnail_path' => null,
                                'caption' => $block['caption'] ?? null,
                                'mime_type' => $file->getClientMimeType(),
                                'size_bytes' => $file->getSize(),
                                'order' => 0,
                                'uploaded_by' => Auth::id(),
                            ]);
                            // Replace temp_media_id with persistent id
                            $blocks[$bi]['media_id'] = $image->id;
                            unset($blocks[$bi]['temp_media_id']);
                        }
                    }
                    // If media_id was already present (unlikely on create) leave intact
                }
            }

            $sectionsContent[$section] = $blocks;
        }

        // Save sections_content if present
        if (!empty($sectionsContent)) {
            $destination->sections_content = $sectionsContent;
            $destination->save();
        }

        DB::commit();

        // Optional legacy gallery handling (if UI uses gallery_images files)
        if ($request->hasFile('gallery_images')) {
            $gallery = $this->handleSectionImages($request, 'gallery_images', 'destinations/gallery');
            if ($gallery) {
                $destination->gallery_images = array_merge($destination->gallery_images ?? [], $gallery);
                $destination->save();
            }
        }

        Log::info('Destination created successfully', ['destination_id' => $destination->id]);

        if ($request->filled('draft')) {
            return response()->json(['success' => true, 'destination_id' => $destination->id], 200);
        }

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
            Log::info('Editing destination', ['destination_id' => $destination->id]);

            $countries = Country::active()->ordered()->get();
            $destination->load('activities', 'country', 'destinationImages');

            return view('admin.destinations.edit', compact('destination', 'countries'));

        } catch (Exception $e) {
            Log::error('Error in adminEdit', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN: Update the specified destination
     */
// Copy & paste this method into your DestinationController class (replace existing adminUpdate).
public function adminUpdate(Request $request, Destination $destination)
{
    try {
        Log::info('Updating destination', [
            'destination_id' => $destination->id,
            'request_data' => $request->except(['image', 'featured_image'])
        ]);

        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug,' . $destination->id,
            'region' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'detailed_overview' => 'nullable|string',
            'what_to_see_do' => 'nullable|string',
            'wildlife_highlights' => 'nullable|string',
            'geography_landscape' => 'nullable|string',
            'best_time_visit' => 'nullable|string',
            'how_to_get_there' => 'nullable|string',
            'accommodation_options' => 'nullable|string',
            'practical_information' => 'nullable|string',
            'cultural_significance' => 'nullable|string',
            'photography_tips' => 'nullable|string',
            'nearby_attractions' => 'nullable|string',
            'interesting_facts' => 'nullable|string',

            // top-level images
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            // geography
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area_size' => 'nullable|integer|min:0',
            'altitude_min' => 'nullable|integer',
            'altitude_max' => 'nullable|integer',

            // pricing
            'entry_fee_foreign' => 'nullable|numeric|min:0',
            'entry_fee_resident' => 'nullable|numeric|min:0',
            'entry_fee_local' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',

            // other
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
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle top-level image replacements
        if ($request->hasFile('image')) {
            if ($destination->image && Storage::disk('public')->exists($destination->image)) {
                Storage::disk('public')->delete($destination->image);
            }
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }
        if ($request->hasFile('featured_image')) {
            if ($destination->featured_image && Storage::disk('public')->exists($destination->featured_image)) {
                Storage::disk('public')->delete($destination->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('destinations/featured', 'public');
        }

        // Booleans
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');

        // Draft handling
        if ($request->filled('draft')) {
            $validated['is_draft'] = true;
            $validated['draft_user_id'] = Auth::id();
        } else {
            $validated['is_draft'] = false;
            $validated['published_at'] = now();
        }

        DB::beginTransaction();

        // Update destination first
        $destination->update($validated);

        // Process block-based sections and uploads (create new images or replace existing)
        $sectionsList = ['overview','activities','wildlife','geography','practical','accommodation','extras'];
        $sectionsContent = $destination->sections_content ?? [];

        foreach ($sectionsList as $section) {
            $blocksJson = $request->input("sections.{$section}.content_blocks");
            if (!$blocksJson) {
                // If no blocks provided for this section, skip (we preserve existing content)
                continue;
            }
            $blocks = json_decode($blocksJson, true) ?: [];
            $uploads = $request->file("sections.{$section}.uploads") ?: [];

            // Process deletions requested from the edit UI
            $toDelete = $request->input("sections.{$section}.delete_media", []);
            if (!empty($toDelete) && is_array($toDelete)) {
                foreach ($toDelete as $mid) {
                    $m = DestinationImage::find($mid);
                    if ($m) {
                        $m->delete(); // model deletes files in booted deleting hook
                    }
                }
            }

            foreach ($blocks as $bi => $block) {
                if (($block['type'] ?? '') === 'image') {
                    // 1) replacement of existing media via upload named media-<id>
                    if (!empty($block['media_id']) && isset($uploads['media-' . $block['media_id']])) {
                        $file = $uploads['media-' . $block['media_id']];
                        $existing = DestinationImage::find($block['media_id']);
                        if ($file && $file->isValid()) {
                            $path = $file->store("destinations/{$section}", 'public');
                            if ($existing) {
                                // delete old file
                                if ($existing->storage_path && Storage::disk('public')->exists($existing->storage_path)) {
                                    Storage::disk('public')->delete($existing->storage_path);
                                }
                                $existing->update([
                                    'storage_path' => $path,
                                    'thumbnail_path' => null,
                                    'caption' => $block['caption'] ?? $existing->caption,
                                    'mime_type' => $file->getClientMimeType(),
                                    'size_bytes' => $file->getSize(),
                                    'uploaded_by' => Auth::id(),
                                ]);
                            } else {
                                // create new record if missing
                                $newImg = DestinationImage::create([
                                    'destination_id' => $destination->id,
                                    'block_id' => $block['id'] ?? null,
                                    'storage_path' => $path,
                                    'thumbnail_path' => null,
                                    'caption' => $block['caption'] ?? null,
                                    'mime_type' => $file->getClientMimeType(),
                                    'size_bytes' => $file->getSize(),
                                    'order' => 0,
                                    'uploaded_by' => Auth::id(),
                                ]);
                                $blocks[$bi]['media_id'] = $newImg->id;
                            }
                        }
                        // remove any temp marker if present
                        unset($blocks[$bi]['temp_media_id']);
                        continue;
                    }

                    // 2) new upload keyed by temp id (tmp-...)
                    if (!empty($block['temp_media_id']) && isset($uploads[$block['temp_media_id']])) {
                        $file = $uploads[$block['temp_media_id']];
                        if ($file && $file->isValid()) {
                            $path = $file->store("destinations/{$section}", 'public');
                            $image = DestinationImage::create([
                                'destination_id' => $destination->id,
                                'block_id' => $block['id'] ?? null,
                                'storage_path' => $path,
                                'thumbnail_path' => null,
                                'caption' => $block['caption'] ?? null,
                                'mime_type' => $file->getClientMimeType(),
                                'size_bytes' => $file->getSize(),
                                'order' => 0,
                                'uploaded_by' => Auth::id(),
                            ]);
                            $blocks[$bi]['media_id'] = $image->id;
                            unset($blocks[$bi]['temp_media_id']);
                        }
                    }
                    // existing media_id is preserved if neither case applies
                }
            }

            // Update local sectionsContent
            $sectionsContent[$section] = $blocks;
        }

        // Persist updated sections_content
        $destination->sections_content = $sectionsContent;
        $destination->save();

        DB::commit();

        Log::info('Destination updated successfully', ['destination_id' => $destination->id]);

        if ($request->filled('draft')) {
            return response()->json(['success' => true, 'destination_id' => $destination->id], 200);
        }

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
            Log::info('Deleting destination', ['destination_id' => $destination->id]);

            // Delete all images (top-level and section-managed)
            if ($destination->image && Storage::disk('public')->exists($destination->image)) {
                Storage::disk('public')->delete($destination->image);
            }
            if ($destination->featured_image && Storage::disk('public')->exists($destination->featured_image)) {
                Storage::disk('public')->delete($destination->featured_image);
            }

            // Delete DestinationImage rows and files (model has deleting hook)
            foreach ($destination->destinationImages as $img) {
                $img->delete();
            }

            $destination->delete();

            Log::info('Destination deleted successfully', ['destination_id' => $destination->id]);

            return redirect()->route('admin.destinations.index')
                            ->with('success', 'Destination deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting destination', [
                'destination_id' => $destination->id,
                'message' => $e->getMessage(),
            ]);
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

            Log::info('Destination status toggled', [
                'destination_id' => $destination->id,
                'new_status' => $destination->is_active
            ]);

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
                // Delete all images
                if ($destination->image) Storage::disk('public')->delete($destination->image);
                if ($destination->featured_image) Storage::disk('public')->delete($destination->featured_image);

                foreach ($destination->destinationImages as $img) {
                    $img->delete();
                }

                $destination->delete();
            }

            Log::info('Bulk delete completed', ['count' => count($request->ids)]);

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

    /**
     * PUBLIC: Display list of destinations
     */
    public function index(Request $request)
    {
        try {
            $query = Destination::with('country')->where('is_active', true);

            // Filter by country
            if ($request->filled('country')) {
                $query->whereHas('country', function ($q) use ($request) {
                    $q->where('code', $request->country);
                });
            }

            // Show only popular
            if ($request->filled('popular')) {
                $query->where('is_popular', true);
            }

            // Search
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%");
                });
            }

            $destinations = $query->orderBy('sort_order')->orderBy('name')->paginate(12);
            $countries = Country::where('is_active', true)->orderBy('name')->get();

            // Get popular destinations - prioritize Uganda destinations (at least 5)
            $ugandaDestinations = Destination::where('is_active', true)
                ->whereHas('country', function($query) {
                    $query->where('code', 'UG');
                })
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(5)
                ->get();

            // Get other popular destinations
            $otherPopular = Destination::where('is_active', true)
                ->where('is_popular', true)
                ->whereDoesntHave('country', function($query) {
                    $query->where('code', 'UG');
                })
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(1)
                ->get();

            $popularDestinations = $ugandaDestinations->merge($otherPopular);

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
                                      ->with('country', 'activities', 'destinationImages')
                                      ->active()
                                      ->firstOrFail();

            // Get related destinations from same country
            $relatedDestinations = Destination::where('country_id', $destination->country_id)
                                             ->where('id', '!=', $destination->id)
                                             ->active()
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
                                       ->active()
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
            $destinations = Destination::active()
                                       ->popular()
                                       ->ordered()
                                       ->limit(6)
                                       ->get();
            return response()->json($destinations);

        } catch (Exception $e) {
            Log::error('Error getting popular destinations', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Error loading destinations'], 500);
        }
    }

    /**
     * Helper: Handle section images upload with sections and captions (legacy)
     */
    private function handleSectionImages($request, $fieldName, $storagePath)
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $images = [];
        $files = $request->file($fieldName);
        $sections = $request->input("{$fieldName}_sections", []);
        $captions = $request->input("{$fieldName}_captions", []);

        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                $path = $file->store($storagePath, 'public');
                $images[] = [
                    'image' => $path,
                    'section' => $sections[$index] ?? '',
                    'caption' => $captions[$index] ?? '',
                ];
            }
        }

        return $images;
    }

    /**
     * Helper: Delete section images (legacy arrays)
     */
    private function deleteSectionImages($images)
    {
        if (empty($images)) return;

        foreach ($images as $imageData) {
            if (isset($imageData['image']) && Storage::disk('public')->exists($imageData['image'])) {
                Storage::disk('public')->delete($imageData['image']);
            }
        }
    }
}