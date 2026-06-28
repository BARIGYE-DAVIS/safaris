<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'seo_title',
        'focus_keyword',
        'featured_image',
        'status',
    ];

    public function blocks()
    {
        return $this->hasMany(SeoPageBlock::class)->orderBy('sort_order');
    }

    public function links()
    {
        return $this->hasMany(SeoPageLink::class);
    }
}