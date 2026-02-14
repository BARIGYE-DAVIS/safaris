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
        'block_id',
        'storage_path',
        'thumbnail_path',
        'caption',
        'mime_type',
        'size_bytes',
        'order',
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
     * Clean up files from disk when the model is deleted.
     */
    protected static function booted()
    {
        static::deleting(function (self $img) {
            if ($img->storage_path) {
                Storage::disk('public')->delete($img->storage_path);
            }
            if ($img->thumbnail_path) {
                Storage::disk('public')->delete($img->thumbnail_path);
            }
        });
    }
}