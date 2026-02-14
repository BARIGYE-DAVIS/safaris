<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourItinerary extends Model
{
    use HasFactory;

    protected $table = 'tour_itinerary';

    protected $fillable = [
        'tour_id',
        'day_number',
        'day_title',
        'activity',
        'accommodation',
        'meals',
        'content_blocks',   // JSON block list
        'cover_media_id',
        'cover_caption',
        'updated_by',
    ];

    /**
     * Cast content_blocks to array for easy use in PHP.
     */
    protected $casts = [
        'content_blocks' => 'array',
        'day_number' => 'integer',
    ];

    // Relationships

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    /**
     * All images attached to this itinerary day.
     */
    public function images()
    {
        return $this->hasMany(TourItineraryImage::class, 'tour_itinerary_id');
    }

    /**
     * Optional cover media (quick lookup). Assumes cover_media_id points to tour_itinerary_images.id
     */
    public function coverMedia()
    {
        return $this->belongsTo(TourItineraryImage::class, 'cover_media_id');
    }
}