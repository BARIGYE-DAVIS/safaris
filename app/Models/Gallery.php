<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery_images';

    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'caption',
        'alt_text',
        'meta_description',
        'meta_keywords',
        'category',
        'tags',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title
        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = static::generateUniqueSlug($gallery->title);
            }
        });

        static::updating(function ($gallery) {
            if ($gallery->isDirty('title') && !$gallery->isDirty('slug')) {
                $gallery->slug = static::generateUniqueSlug($gallery->title, $gallery->id);
            }
        });
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
                    ->when($ignoreId, function($query) use ($ignoreId) {
                        return $query->where('id', '!=', $ignoreId);
                    })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get image alt text with fallback
     */
    public function getAltTextAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get meta description with fallback
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit($this->caption, 155);
    }

    /**
     * Get formatted tags
     */
    public function getFormattedTagsAttribute()
    {
        if (!$this->tags || !is_array($this->tags)) {
            return [];
        }
        
        return collect($this->tags)
                ->filter()
                ->map(function($tag) {
                    return trim($tag);
                })
                ->unique()
                ->values()
                ->all();
    }

    /**
     * Scope: Visible images only
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Search by title, caption, tags, or keywords
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('caption', 'like', "%{$search}%")
              ->orWhere('alt_text', 'like', "%{$search}%")
              ->orWhere('meta_keywords', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    /**
     * Get structured data for SEO
     */
    public function getStructuredDataAttribute()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ImageObject',
            'contentUrl' => $this->image_url,
            'name' => $this->title,
            'description' => $this->meta_description,
            'caption' => $this->caption,
            'keywords' => $this->meta_keywords,
            'dateCreated' => $this->created_at->toISOString(),
            'uploadDate' => $this->created_at->toISOString(),
        ];
    }
}