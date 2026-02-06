<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text_1',
        'button_link_1',
        'button_color_1',
        'button_text_2',
        'button_link_2',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if it's a full URL (like unsplash)
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            // Otherwise it's a storage path
            return asset('storage/' . $this->image);
        }
        
        return asset('images/placeholder-hero.jpg');
    }

    /**
     * Get button 1 color classes
     */
    public function getButton1ColorClassAttribute()
    {
        $colors = [
            'green' => 'bg-green-600 hover:bg-green-700',
            'orange' => 'bg-orange-600 hover:bg-orange-700',
            'yellow' => 'bg-yellow-600 hover:bg-yellow-700',
            'blue' => 'bg-blue-600 hover:bg-blue-700',
            'red' => 'bg-red-600 hover:bg-red-700',
            'purple' => 'bg-purple-600 hover:bg-purple-700',
        ];

        return $colors[$this->button_color_1] ?? 'bg-green-600 hover:bg-green-700';
    }

    /**
     * Scope: Only active slides
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordered slides
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}