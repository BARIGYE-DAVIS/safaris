<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DestinationImage extends Model
{
    use HasFactory;

    /**
     * Table name is explicit for clarity (matches migration).
     */
    protected $table = 'destination_images';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'destination_id',
        'block_id',        // Links image to a specific block (e.g., 'blk-123')
        'storage_path',
        'thumbnail_path',
        'caption',         // Displayed below image
        'alt_text',        // For accessibility / SEO
        'mime_type',
        'size_bytes',
        'order',           // Sort order within the block
        'uploaded_by',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'size_bytes' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Destination this image belongs to.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * User who uploaded the image (optional).
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Public URL for the stored image (disk: public).
     */
    public function getUrlAttribute()
    {
        return $this->storage_path ? asset('storage/' . ltrim($this->storage_path, '/')) : null;
    }

    /**
     * Public URL for the thumbnail (fallback to main image if none).
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . ltrim($this->thumbnail_path, '/'));
        }

        return $this->storage_path ? asset('storage/' . ltrim($this->storage_path, '/')) : null;
    }

    /**
     * Get the block ID this image belongs to.
     * Used for linking images to specific blocks during rendering.
     */
    public function getBlockIdAttribute($value)
    {
        return $value;
    }

    /**
     * Scope: Get images for a specific block.
     */
    public function scopeForBlock($query, $blockId)
    {
        return $query->where('block_id', $blockId);
    }

    /**
     * Scope: Get images for a specific destination.
     */
    public function scopeForDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    /**
     * Scope: Order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Clean up files from disk when the model is deleted.
     */
    protected static function booted()
    {
        static::deleting(function (self $img) {
            // Delete main image
            if ($img->storage_path) {
                $fullPath = public_path('storage/' . ltrim($img->storage_path, '/'));
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            // Delete thumbnail
            if ($img->thumbnail_path) {
                $fullPath = public_path('storage/' . ltrim($img->thumbnail_path, '/'));
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        });

        // When deleting a destination, all its images will be deleted automatically
        // because of the foreign key cascade, or the destination's deleting event.
    }

    /**
     * Get the caption with fallback.
     */
    public function getDisplayCaptionAttribute()
    {
        return $this->caption ?? '';
    }

    /**
     * Get the alt text with fallback to caption.
     */
    public function getDisplayAltAttribute()
    {
        return $this->alt_text ?? $this->caption ?? '';
    }

    /**
     * Get image dimensions (if available).
     * Useful for responsive images.
     */
    public function getDimensionsAttribute()
    {
        $path = public_path('storage/' . ltrim($this->storage_path, '/'));
        if (file_exists($path)) {
            $size = getimagesize($path);
            if ($size) {
                return [
                    'width' => $size[0],
                    'height' => $size[1],
                ];
            }
        }
        return null;
    }

    /**
     * Get file size formatted.
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->size_bytes) {
            return null;
        }

        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }
}