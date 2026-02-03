<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourItinerary;
use App\Models\TourPrice;
use App\Models\TourImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminTourController extends Controller
{
    public function index()
    {
        $tours = Tour::latest()->paginate(10);
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.create');
    }

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

        $tour = Tour::create($validated);

        // Itinerary days (array input)
        if ($request->has('itinerary')) {
            foreach ($request->input('itinerary') as $day) {
                $tour->itinerary()->create([
                    'day_number'    => $day['day_number'] ?? null,
                    'activity'      => $day['activity'] ?? '',
                    'day_title'     => $day['day_title'] ?? '',
                    'accommodation' => $day['accommodation'] ?? '',
                    'meals'         => $day['meals'] ?? '',
                ]);
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

        // Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('tours/images', 'public');
                $tour->images()->create([
                    'image_path' => $imgPath,
                    'uploaded_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour, itinerary, prices, and images created!');
    }

    public function edit($id)
    {
        $tour = Tour::with(['itinerary', 'prices', 'images'])->findOrFail($id);
        return view('admin.tours.edit', compact('tour'));
    }

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

        $tour->update($validated);

        // ============================================
        // UPDATE ITINERARIES
        // ============================================
        if ($request->has('itinerary')) {
            $submittedItineraryIds = [];

            foreach ($request->input('itinerary') as $dayData) {
                if (isset($dayData['id']) && $dayData['id']) {
                    // Update existing itinerary
                    $itinerary = TourItinerary::where('tour_id', $tour->id)
                        ->where('id', $dayData['id'])
                        ->first();
                    
                    if ($itinerary) {
                        $itinerary->update([
                            'day_number'    => $dayData['day_number'] ?? null,
                            'activity'      => $dayData['activity'] ?? '',
                            'day_title'     => $dayData['day_title'] ?? '',
                            'accommodation' => $dayData['accommodation'] ?? '',
                            'meals'         => $dayData['meals'] ?? '',
                        ]);
                        $submittedItineraryIds[] = $itinerary->id;
                    }
                } else {
                    // Create new itinerary
                    $newItinerary = $tour->itineraries()->create([
                        'day_number'    => $dayData['day_number'] ?? null,
                        'activity'      => $dayData['activity'] ?? '',
                        'day_title'     => $dayData['day_title'] ?? '',
                        'accommodation' => $dayData['accommodation'] ?? '',
                        'meals'         => $dayData['meals'] ?? '',
                    ]);
                    $submittedItineraryIds[] = $newItinerary->id;
                }
            }

            // Delete itineraries that were removed (not in submitted list)
            TourItinerary::where('tour_id', $tour->id)
                ->whereNotIn('id', $submittedItineraryIds)
                ->delete();
        } else {
            // If no itinerary submitted, delete all existing
            $tour->itineraries()->delete();
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
        // UPDATE IMAGES
        // ============================================
        
        // Handle deleted images
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

        // Handle new images
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

        return redirect()->route('admin.tours.edit', $tour->id)
            ->with('success', 'Tour and all related data updated successfully!');
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
        
        // Delete related records (if you have cascade delete in DB, this is optional)
        $tour->itineraries()->delete();
        $tour->prices()->delete();
        $tour->images()->delete();
        
        // Delete the tour
        $tour->delete();

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour and all related records deleted!');
    }
}