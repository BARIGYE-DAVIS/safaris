<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'location',
        'destination_id',
        'country_id',
        'category',
        'currency',
        'price_from',
        'price_to',
        'short_description',
        'full_description',
        'featured_image',
        'amenities',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'amenities'   => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'price_from'  => 'decimal:2',
        'price_to'    => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function images()
    {
        return $this->hasMany(AccommodationImage::class)->orderBy('sort_order');
    }

    /**
     * NEW: Relationship to tour itineraries
     * An accommodation can be used in many tour itinerary days
     */
    public function itineraries()
    {
        return $this->hasMany(TourItinerary::class, 'accommodation_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayPriceRangeAttribute(): ?string
    {
        if (is_null($this->price_from) && is_null($this->price_to)) {
            return null;
        }

        $currency = $this->currency ?? 'USD';
        $from     = $this->price_from ? number_format((float) $this->price_from, 0) : null;
        $to       = $this->price_to   ? number_format((float) $this->price_to, 0)   : null;

        if ($from && $to) {
            return "{$currency} {$from} – {$to} per person per night, sharing";
        }

        if ($from) {
            return "From {$currency} {$from} per person per night, sharing";
        }

        return "{$currency} {$to} per person per night, sharing";
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        return asset('storage/' . $this->featured_image);
    }

    /**
     * FIX: Uses already-loaded 'images' relation to avoid N+1 query.
     * If the relation is not loaded (e.g. called outside a list context),
     * it falls back to a single DB query.
     */
    public function getFirstGalleryImageUrlAttribute(): ?string
    {
        $image = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        return $image ? $image->url : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function (Accommodation $accommodation) {
            if (empty($accommodation->slug)) {
                $accommodation->slug = Str::slug($accommodation->name) . '-' . Str::random(5);
            }
        });

        static::updating(function (Accommodation $accommodation) {
            if (empty($accommodation->slug)) {
                $accommodation->slug = Str::slug($accommodation->name) . '-' . Str::random(5);
            }
        });
    }
}