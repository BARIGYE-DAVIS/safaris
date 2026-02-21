<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'path',
        'caption',
        'alt_text',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}