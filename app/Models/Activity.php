<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'destination_id',
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'featured_image', // NEW - For header background
        'overview', // NEW
        'what_to_expect', // NEW
        'highlights', // NEW
        'inclusions', // NEW - JSON
        'exclusions', // NEW - JSON
        'equipment_provided', // NEW - JSON
        'skill_levels', // NEW - JSON
        'best_times', // NEW - JSON
        'what_to_bring', // NEW - JSON
        'pricing_packages', // NEW - JSON
        'faqs', // NEW - JSON
        'regulations', // NEW
        'safety_info', // NEW
        'health_requirements', // NEW
        'cultural_experience', // NEW
        'conservation_info', // NEW
        'booking_info', // NEW - JSON
        'special_notes', // NEW
        'duration', // NEW - e.g., "Full Day", "2-8 hours"
        'difficulty_level', // NEW - easy, moderate, challenging, extreme
        'min_age', // NEW
        'max_group_size', // NEW
        'price_from', // NEW
        'price_to', // NEW
        'currency', // NEW
        'meta_title', // NEW - SEO
        'meta_description', // NEW - SEO
        'meta_keywords', // NEW - SEO
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'equipment_provided' => 'array',
        'skill_levels' => 'array',
        'best_times' => 'array',
        'what_to_bring' => 'array',
        'pricing_packages' => 'array',
        'faqs' => 'array',
        'booking_info' => 'array',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'min_age' => 'integer',
        'max_group_size' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->name);
            }
        });

        static::updating(function ($activity) {
            if ($activity->isDirty('name') && empty($activity->slug)) {
                $activity->slug = Str::slug($activity->name);
            }
        });
    }

    /**
     * Get the category this activity belongs to
     */
    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id');
    }

    /**
     * Get the destination this activity belongs to
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get all countries where this activity is available
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'activity_country')
                    ->withPivot('is_available')
                    ->withTimestamps();
    }

    // ADD THIS
public function destinations()
{
    return $this->belongsToMany(Destination::class, 'activity_destination')
                ->withTimestamps();
}

    /**
     * Get all gallery images for this activity (MULTIPLE IMAGES)
     */
    public function images()
    {
        return $this->hasMany(ActivityImage::class)->orderBy('sort_order');
    }

    /**
     * Get the featured gallery image
     */
    public function featuredGalleryImage()
    {
        return $this->hasOne(ActivityImage::class)->where('is_featured', true);
    }

    /**
     * Scope: Get only active activities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get only popular activities
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
     * Scope: Filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Filter by country
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->whereHas('countries', function ($q) use ($countryId) {
            $q->where('countries.id', $countryId)
              ->where('activity_country.is_available', true);
        });
    }

    /**
     * Scope: Filter by difficulty level
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * Scope: Filter by price range
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price_from', [$minPrice, $maxPrice]);
    }

    /**
     * Get icon URL
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return asset('storage/' . $this->icon);
        }
        return null;
    }

    /**
     * Get image URL (thumbnail/main image)
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-activity.jpg');
    }

    /**
     * Get featured image URL for header background
     * Priority: featured_image -> featuredGalleryImage -> image -> default
     */
    public function getFeaturedImageUrlAttribute()
    {
        // 1. Check if featured_image field is set
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        // 2. Check if there's a featured gallery image
        if ($this->featuredGalleryImage) {
            return asset('storage/' . $this->featuredGalleryImage->image_path);
        }
        
        // 3. Fallback to main image
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // 4. Default image
        return asset('images/default-activity-header.jpg');
    }

    /**
     * Get all gallery image URLs
     */
    public function getGalleryImagesAttribute()
    {
        return $this->images->map(function($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'title' => $image->title,
                'caption' => $image->caption,
                'is_featured' => $image->is_featured
            ];
        });
    }

    /**
     * Get countries names as comma-separated string
     */
    public function getCountriesListAttribute()
    {
        return $this->countries->pluck('name')->implode(', ');
    }

    /**
     * Get formatted price range
     */
    public function getPriceRangeAttribute()
    {
        if ($this->price_from && $this->price_to) {
            return $this->currency . ' ' . number_format($this->price_from, 2) . ' - ' . number_format($this->price_to, 2);
        } elseif ($this->price_from) {
            return 'From ' . $this->currency . ' ' . number_format($this->price_from, 2);
        }
        return 'Price on request';
    }

    /**
     * Get difficulty level badge class
     */
    public function getDifficultyBadgeClassAttribute()
    {
        return match($this->difficulty_level) {
            'easy' => 'badge-success',
            'moderate' => 'badge-info',
            'challenging' => 'badge-warning',
            'extreme' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get difficulty level display name
     */
    public function getDifficultyDisplayAttribute()
    {
        return ucfirst($this->difficulty_level ?? 'Not specified');
    }

    /**
     * Check if activity has detailed content
     */
    public function hasDetailedContent()
    {
        return !empty($this->overview) || 
               !empty($this->what_to_expect) || 
               !empty($this->inclusions) || 
               $this->images()->count() > 0;
    }


    /**
 * Check if this activity is in any custom tour requests
 */
public function getCustomTourRequestsCountAttribute()
{
    return CustomTourRequest::whereJsonContains('activities', $this->id)->count();
}


/**
 * Get custom tour requests that include this activity
 */
public function getCustomTourRequestsAttribute()
{
    return CustomTourRequest::whereJsonContains('activities', $this->id)
                ->orderBy('created_at', 'desc')
                ->get();
}

    /**
     * Get meta title for SEO (fallback to name)
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name . ' - ' . config('app.name');
    }

    /**
     * Get meta description for SEO (fallback to description)
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->description), 160);
    }


    public function options(): BelongsToMany
    {
        return $this->belongsToMany(ActivityOption::class, 'activity_activity_option')
            ->withTimestamps();
    }

    public function bringOptions(): BelongsToMany
    {
        return $this->options()->where('activity_options.type', 'bring');
    }

    public function includedOptions(): BelongsToMany
    {
        return $this->options()->where('activity_options.type', 'included');
    }

    public function excludedOptions(): BelongsToMany
    {
        return $this->options()->where('activity_options.type', 'excluded');
    }
}