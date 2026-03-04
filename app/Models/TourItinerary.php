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
        'accommodation',       // text column (backward compat)
        'accommodation_id',    // FK to accommodations table
        'meals',
        'content_blocks',
        'cover_media_id',
        'cover_caption',
        'updated_by',
    ];

    protected $casts = [
        'content_blocks' => 'array',
        'day_number'     => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    /**
     * The Accommodation record linked to this itinerary day.
     *
     * IMPORTANT: Named 'accommodationRecord' — NOT 'accommodation',
     * because 'accommodation' is also a plain text column on this table.
     * Naming the relationship 'accommodation' causes $item->accommodation
     * to return the Eloquent relation proxy instead of the column string,
     * breaking every blade/JS that reads the text value.
     */
    public function accommodationRecord()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    /**
     * All images belonging to the linked accommodation (via hasManyThrough).
     */
    public function accommodationImages()
    {
        return $this->hasManyThrough(
            AccommodationImage::class,
            Accommodation::class,
            'id',               // FK on accommodations
            'accommodation_id', // FK on accommodation_images
            'accommodation_id', // local key on tour_itinerary
            'id'                // local key on accommodations
        );
    }

    /**
     * Activity / day-level images uploaded directly to this itinerary day.
     */
    public function images()
    {
        return $this->hasMany(TourItineraryImage::class, 'tour_itinerary_id');
    }
}