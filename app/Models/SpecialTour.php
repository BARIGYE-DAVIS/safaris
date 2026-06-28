<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpecialTour extends Model
{
    use HasFactory;

    protected $table = 'special_tours';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'whats_included',
        'whats_not_included',
        'price',
        'currency',
        'price_note',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * A special tour has many images.
     */
    public function images()
    {
        return $this->hasMany(SpecialTourImage::class, 'special_tour_id')->orderBy('sort_order');
    }

    /**
     * Optional helper: generate a unique slug when creating.
     * (You can also do this in the controller instead.)
     */
    protected static function booted()
    {
        static::creating(function (SpecialTour $tour) {
            if (empty($tour->slug) && !empty($tour->title)) {
                $tour->slug = Str::slug($tour->title);
            }
        });
    }
}