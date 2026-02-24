<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'destinations',
        'type',
        'status',
        'description',
        'included',
        'excluded',
        'meta_keywords',
        'meta_description',
        'meta_title',
        'featured_image',
    ];

    // Relationships

    public function itinerary() {
        return $this->hasMany(\App\Models\TourItinerary::class);
    }

    // Add this alias for plural usage
    public function itineraries() {
        return $this->hasMany(\App\Models\TourItinerary::class);
    }

    public function prices() {
        return $this->hasMany(\App\Models\TourPrice::class);
    }

    /**
 * Scope: Only published tours
 */
    public function scopePublished($query)
    {
    return $query->where('status', 'published');
    }

    public function images() {
        return $this->hasMany(\App\Models\TourImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }
} 