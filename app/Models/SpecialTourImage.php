<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialTourImage extends Model
{
    use HasFactory;

    protected $table = 'special_tour_images';

    protected $fillable = [
        'special_tour_id',
        'image_path',
        'alt_text',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Each image belongs to one special tour.
     */
    public function specialTour()
    {
        return $this->belongsTo(SpecialTour::class, 'special_tour_id');
    }
}