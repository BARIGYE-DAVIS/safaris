<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'image_path',
        'title',
        'caption',
        'sort_order',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the activity that owns the image
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When setting as featured, unset other featured images
        static::saving(function ($image) {
            if ($image->is_featured) {
                static::where('activity_id', $image->activity_id)
                      ->where('id', '!=', $image->id)
                      ->update(['is_featured' => false]);
            }
        });
    }
}