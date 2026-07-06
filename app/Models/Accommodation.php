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
        'content_blocks',
        'featured_image',
        'amenities',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'focus_keyword',
    ];

    protected $casts = [
        'amenities'      => 'array',
        'content_blocks' => 'array',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'price_from'     => 'decimal:2',
        'price_to'       => 'decimal:2',
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
     * Relationship to tour itineraries
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

    // Debug: Log the path
    \Log::info('Featured image path: ' . $this->featured_image);
    \Log::info('Featured image URL: ' . asset('storage/' . $this->featured_image));

    return asset('storage/' . $this->featured_image);
}

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