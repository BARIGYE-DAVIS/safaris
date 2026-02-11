<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Automatically generate a slug from the name when creating/updating
     * if no slug was provided.
     */
    protected static function booted()
    {
        static::creating(function (BlogCategory $category) {
            if (empty($category->slug) && ! empty($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (BlogCategory $category) {
            if (empty($category->slug) && ! empty($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relationship: a category has many blog posts.
     * Update the related model class name if your post model differs.
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Blog::class);
    }
}