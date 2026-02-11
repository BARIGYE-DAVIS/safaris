<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'country_id',
        'region',
        'type',
        'description',
        'detailed_overview',
        'what_to_see_do',
        'wildlife_highlights',
        'geography_landscape',
        'best_time_visit',
        'how_to_get_there',
        'accommodation_options',
        'practical_information',
        'cultural_significance',
        'photography_tips',
        'nearby_attractions',
        'interesting_facts',
        'overview_images',
        'activities_images',
        'wildlife_images',
        'landscape_images',
        'accommodation_images',
        'gallery_images',
        'featured_image',
        'image',
        'latitude',
        'longitude',
        'area_size',
        'area_unit',
        'altitude_min',
        'altitude_max',
        'entry_fee_foreign',
        'entry_fee_resident',
        'entry_fee_local',
        'currency',
        'established_year',
        'annual_visitors',
        'phone',
        'email',
        'website',
        'opening_hours',
        'best_season',
        'climate',
        'avg_temp_high',
        'avg_temp_low',
        'rainfall_annual',
        'is_popular',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'overview_images' => 'array',
        'activities_images' => 'array',
        'wildlife_images' => 'array',
        'landscape_images' => 'array',
        'accommodation_images' => 'array',
        'gallery_images' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'entry_fee_foreign' => 'decimal:2',
        'entry_fee_resident' => 'decimal:2',
        'entry_fee_local' => 'decimal:2',
        'area_size' => 'integer',
        'altitude_min' => 'integer',
        'altitude_max' => 'integer',
        'annual_visitors' => 'integer',
        'avg_temp_high' => 'integer',
        'avg_temp_low' => 'integer',
        'rainfall_annual' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name') && empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    /**
     * Get the country this destination belongs to
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get all activities in this destination
     */
    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('sort_order');
    }

    /**
     * Get all tours visiting this destination
     */
    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_destinations')
                    ->withPivot('day_number', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Get the main image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Parse overview images with inline content
     */
    public function getOverviewWithImagesAttribute()
    {
        return $this->parseContentWithImages($this->detailed_overview, $this->overview_images);
    }

    /**
     * Parse activities with inline images
     */
    public function getActivitiesWithImagesAttribute()
    {
        return $this->parseContentWithImages($this->what_to_see_do, $this->activities_images);
    }

    /**
     * Parse wildlife with inline images
     */
    public function getWildlifeWithImagesAttribute()
    {
        return $this->parseContentWithImages($this->wildlife_highlights, $this->wildlife_images);
    }

    /**
     * Helper: Parse content and inject images
     */
    private function parseContentWithImages($content, $images)
    {
        if (empty($content) || empty($images)) {
            return $content;
        }

        // Images array format: [{'section': 'Wildlife Game Drives', 'image': 'path.jpg', 'caption': 'text'}]
        foreach ($images as $imageData) {
            $section = $imageData['section'] ?? '';
            $imagePath = $imageData['image'] ?? '';
            $caption = $imageData['caption'] ?? '';

            if ($section && $imagePath) {
                $imageHtml = '<div class="inline-image my-4"><img src="' . asset('storage/' . $imagePath) . '" alt="' . $caption . '" class="rounded-lg shadow-md max-w-full">';
                if ($caption) {
                    $imageHtml .= '<p class="text-sm text-gray-600 italic mt-2">' . $caption . '</p>';
                }
                $imageHtml .= '</div>';

                // Insert image after the section heading
                $content = str_replace($section . ':**', $section . ':**' . $imageHtml, $content);
            }
        }

        return $content;
    }

    /**
     * Scope: Get only active destinations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get only popular destinations
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope: Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope: Filter by country
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Scope: Filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get active activities count
     */
    public function getActiveActivitiesCountAttribute()
    {
        return $this->activities()->where('is_active', true)->count();
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude
            ];
        }
        return null;
    }
        

    /**
     * Get altitude range formatted
     */
    public function getAltitudeRangeAttribute()
    {
        if ($this->altitude_min && $this->altitude_max) {
            return $this->altitude_min . 'm - ' . $this->altitude_max . 'm';
        }
        return null;
    }


    /**
 * Check if this destination is in any custom tour requests
 */
public function getCustomTourRequestsCountAttribute()
{
    return CustomTourRequest::whereJsonContains('destinations', $this->id)->count();
}

/**
 * Get custom tour requests that include this destination
 */
public function getCustomTourRequestsAttribute()
{
    return CustomTourRequest::whereJsonContains('destinations', $this->id)
                ->orderBy('created_at', 'desc')
                ->get();
}


    /**
     * Get area formatted
     */
    public function getAreaFormattedAttribute()
    {
        if ($this->area_size) {
            return number_format($this->area_size) . ' ' . $this->area_unit;
        }
        return null;
    }
}