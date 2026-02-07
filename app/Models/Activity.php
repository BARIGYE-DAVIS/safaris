<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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
     * Get all countries where this activity is available
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'activity_country')
                    ->withPivot('is_available')
                    ->withTimestamps();
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
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-activity.jpg'); // Default image
    }

    /**
     * Get countries names as comma-separated string
     */
    public function getCountriesListAttribute()
    {
        return $this->countries()->pluck('name')->implode(', ');
    }
}