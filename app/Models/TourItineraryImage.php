<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourItineraryImage extends Model
{
    use HasFactory;

    protected $table = 'tour_itinerary_images';

    protected $fillable = [
        'tour_itinerary_id',
        'block_id',
        'storage_path',
        'thumbnail_path',
        'caption',
        'alt_text',
        'mime_type',
        'size_bytes',
        'order',
        'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'order' => 'integer',
    ];

    public function itinerary()
    {
        return $this->belongsTo(TourItinerary::class, 'tour_itinerary_id');
    }

    /**
     * Full public URL for the image (assumes public storage disk / storage:link).
     */
    public function getUrlAttribute()
    {
        return $this->storage_path ? asset('storage/' . ltrim($this->storage_path, '/')) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . ltrim($this->thumbnail_path, '/')) : $this->url;
    }
}