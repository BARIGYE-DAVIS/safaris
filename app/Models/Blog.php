<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'featured_image',
        'excerpt',
        'content',
        'content_json',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'category_id',
        'tags',
        'author_name', // CHANGED: from author_id to author_name
        'status',
        'published_at',
        'is_featured',
        'views_count',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content_json' => 'array',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    // REMOVED: author() relationship - no longer exists

    public function images()
    {
        return $this->hasMany(BlogImage::class)->orderBy('order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query, $limit = 5)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    // Accessors & Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getReadingTimeAttribute($value)
    {
        if ($value) {
            return $value;
        }
        // Auto-calculate reading time (200 words per minute)
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200);
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 200);
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    // Helper Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getTagsArray()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }
}