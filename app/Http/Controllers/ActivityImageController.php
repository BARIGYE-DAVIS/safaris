<?php

namespace App\Http\Controllers;

use App\Models\ActivityImage;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityImageController extends Controller
{
    /**
     * ADMIN: Delete a gallery image
     */
    public function destroy(ActivityImage $activityImage)
    {
        // Delete from storage
        Storage::disk('public')->delete($activityImage->image_path);
        
        // Delete from database
        $activityImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!'
        ]);
    }

    /**
     * ADMIN: Set image as featured
     */
    public function setFeatured(ActivityImage $activityImage)
    {
        // Unset all other featured images for this activity
        ActivityImage::where('activity_id', $activityImage->activity_id)
                    ->update(['is_featured' => false]);
        
        // Set this image as featured
        $activityImage->update(['is_featured' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Image set as featured successfully!'
        ]);
    }

    /**
     * ADMIN: Update image details (title, caption, sort order)
     */
    public function update(Request $request, ActivityImage $activityImage)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $activityImage->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully!',
            'image' => $activityImage
        ]);
    }

    /**
     * ADMIN: Reorder gallery images
     */
    public function reorder(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:activity_images,id',
            'images.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($validated['images'] as $imageData) {
            ActivityImage::where('id', $imageData['id'])
                        ->where('activity_id', $activity->id)
                        ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Images reordered successfully!'
        ]);
    }

    /**
     * ADMIN: Upload additional gallery images via AJAX
     */
    public function upload(Request $request, Activity $activity)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $uploadedImages = [];
        $currentMaxSort = $activity->images()->max('sort_order') ?? 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('activities/gallery', 'public');
                
                $activityImage = $activity->images()->create([
                    'image_path' => $path,
                    'title' => $request->input("titles.{$index}"),
                    'caption' => $request->input("captions.{$index}"),
                    'sort_order' => $currentMaxSort + $index + 1,
                    'is_featured' => false
                ]);

                $uploadedImages[] = [
                    'id' => $activityImage->id,
                    'url' => asset('storage/' . $activityImage->image_path),
                    'title' => $activityImage->title,
                    'caption' => $activityImage->caption,
                    'sort_order' => $activityImage->sort_order
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedImages) . ' image(s) uploaded successfully!',
            'images' => $uploadedImages
        ]);
    }
}