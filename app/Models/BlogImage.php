<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'image_path',
        'alt_text',
        'caption',
        'order',
        'type',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}