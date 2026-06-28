<?php

namespace App\Http\Controllers;

use App\Models\SpecialTour;
use App\Models\SpecialTourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminSpecialToursController extends Controller
{
    /**
     * ADMIN: List special tours
     */
    public function index()
    {
        $specialTours = SpecialTour::query()
            ->latest()
            ->paginate(20);

        return view('admin.special-tours.index', compact('specialTours'));
    }

    /**
     * ADMIN: Show create form
     */
    public function create()
    {
        return view('admin.special-tours.create');
    }

    /**
     * ADMIN: Store new special tour + multiple images
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request) {
            $slugSource = $request->input('slug') ?: $request->input('title');
            $slug = $this->makeUniqueSlug($slugSource);

            $specialTour = SpecialTour::create([
                'title' => $request->input('title'),
                'slug' => $slug,
                'description' => $request->input('description'),

                'whats_included' => $request->input('whats_included'),
                'whats_not_included' => $request->input('whats_not_included'),

                'price' => $request->input('price'),
                'currency' => $request->input('currency', 'UGX'),
                'price_note' => $request->input('price_note'),

                'is_active' => (bool) $request->boolean('is_active', true),
            ]);

            $this->saveUploadedImages($specialTour, $request);

            return redirect()
                ->route('admin.special-tours.edit', $specialTour->id)
                ->with('success', 'Special tour created successfully.');
        });
    }

    /**
     * ADMIN: Show edit form
     */
    public function edit(int $id)
    {
        $specialTour = SpecialTour::query()
            ->with(['images'])
            ->findOrFail($id);

        return view('admin.special-tours.edit', compact('specialTour'));
    }

    /**
     * ADMIN: Update tour + optionally upload additional images
     */
    public function update(Request $request, int $id)
    {
        $specialTour = SpecialTour::query()
            ->with(['images'])
            ->findOrFail($id);

        $validator = $this->validator($request->all(), $specialTour->id);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request, $specialTour) {
            // Only change slug if admin provided it
            if ($request->filled('slug')) {
                $specialTour->slug = $this->makeUniqueSlug($request->input('slug'), $specialTour->id);
            }

            $specialTour->fill([
                'title' => $request->input('title'),
                'description' => $request->input('description'),

                'whats_included' => $request->input('whats_included'),
                'whats_not_included' => $request->input('whats_not_included'),

                'price' => $request->input('price'),
                'currency' => $request->input('currency', 'UGX'),
                'price_note' => $request->input('price_note'),

                // Activate / Inactivate switch in edit form
                'is_active' => (bool) $request->boolean('is_active', $specialTour->is_active),
            ]);

            $specialTour->save();

            // Append new images if uploaded
            $this->saveUploadedImages($specialTour, $request);

            return redirect()
                ->route('admin.special-tours.edit', $specialTour->id)
                ->with('success', 'Special tour updated successfully.');
        });
    }

    /**
     * ADMIN: Delete special tour + delete image files
     */
    public function destroy(int $id)
    {
        $specialTour = SpecialTour::query()
            ->with(['images'])
            ->findOrFail($id);

        return DB::transaction(function () use ($specialTour) {
            foreach ($specialTour->images as $img) {
                if (!empty($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }

            // FK cascade removes special_tour_images rows
            $specialTour->delete();

            return redirect()
                ->route('admin.special-tours.index')
                ->with('success', 'Special tour deleted successfully.');
        });
    }

    /**
     * ADMIN: Quick activate (button in edit view)
     */
    public function activate(int $id)
    {
        $specialTour = SpecialTour::findOrFail($id);
        $specialTour->update(['is_active' => true]);

        return back()->with('success', 'Special tour activated.');
    }

    /**
     * ADMIN: Quick deactivate (button in edit view)
     */
    public function deactivate(int $id)
    {
        $specialTour = SpecialTour::findOrFail($id);
        $specialTour->update(['is_active' => false]);

        return back()->with('success', 'Special tour deactivated.');
    }

    /**
     * ADMIN: Delete a single image (button in edit view)
     */
    public function destroyImage(int $imageId)
    {
        $image = SpecialTourImage::query()->findOrFail($imageId);

        return DB::transaction(function () use ($image) {
            $tourId = $image->special_tour_id;

            if (!empty($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $image->delete();

            return redirect()
                ->route('admin.special-tours.edit', $tourId)
                ->with('success', 'Image deleted successfully.');
        });
    }

    /**
     * ADMIN: Reorder images (optional)
     * Expects: image_ids[] in desired order.
     */
    public function updateImageOrder(Request $request, int $tourId)
    {
        $specialTour = SpecialTour::query()->with('images')->findOrFail($tourId);

        $validator = Validator::make($request->all(), [
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:special_tour_images,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $imageIds = $request->input('image_ids', []);

        return DB::transaction(function () use ($specialTour, $imageIds) {
            // Ensure images belong to this tour
            $countBelonging = SpecialTourImage::query()
                ->where('special_tour_id', $specialTour->id)
                ->whereIn('id', $imageIds)
                ->count();

            if ($countBelonging !== count($imageIds)) {
                return back()->with('error', 'Invalid image order request.');
            }

            foreach ($imageIds as $sortOrder => $imageId) {
                SpecialTourImage::where('id', $imageId)->update(['sort_order' => $sortOrder]);
            }

            return back()->with('success', 'Image order updated.');
        });
    }

    /**
     * Shared validator for store/update.
     */
    private function validator(array $data, ?int $ignoreId = null)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'required|string',

            'whats_included' => 'nullable|string',
            'whats_not_included' => 'nullable|string',

            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'price_note' => 'nullable|string|max:255',

            'is_active' => 'nullable|boolean',

            // multiple images
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB each
            'images_alt' => 'nullable|array',
            'images_alt.*' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Ensure slug is unique (special_tours.slug unique).
     */
    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $base = $slug;
        $i = 1;

        while (
            SpecialTour::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Save uploaded images (append).
     * Input names: images[] and optional images_alt[].
     */
    private function saveUploadedImages(SpecialTour $specialTour, Request $request): void
    {
        $uploadedImages = $request->file('images', []);
        if (!$uploadedImages || count($uploadedImages) === 0) {
            return;
        }

        $altTexts = $request->input('images_alt', []);

        $maxSort = $specialTour->images()->max('sort_order');
        $startSort = is_null($maxSort) ? 0 : ((int) $maxSort + 1);

        foreach ($uploadedImages as $offset => $image) {
            $path = $image->store('special-tours', 'public');

            SpecialTourImage::create([
                'special_tour_id' => $specialTour->id,
                'image_path' => $path,
                'alt_text' => $altTexts[$offset] ?? null,
                'sort_order' => $startSort + $offset,
            ]);
        }
    }
}