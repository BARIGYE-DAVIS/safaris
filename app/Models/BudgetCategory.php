<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BudgetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_range_min',
        'price_range_max',
        'currency',
        'description',
        'features',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budgetCategory) {
            if (empty($budgetCategory->slug)) {
                $budgetCategory->slug = Str::slug($budgetCategory->name);
            }
        });

        static::updating(function ($budgetCategory) {
            if ($budgetCategory->isDirty('name') && empty($budgetCategory->slug)) {
                $budgetCategory->slug = Str::slug($budgetCategory->name);
            }
        });
    }

    /**
     * Scope: Get only active budget categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_range_min');
    }

    /**
     * Get formatted price range
     */
    public function getPriceRangeFormattedAttribute()
    {
        if ($this->price_range_min && $this->price_range_max) {
            return $this->currency . ' ' . number_format($this->price_range_min) . ' - ' . 
                   $this->currency . ' ' . number_format($this->price_range_max) . ' per person/day';
        }
        return 'Contact for pricing';
    }

    /**
     * Get formatted features list
     */
    public function getFeaturesListAttribute()
    {
        return $this->features ?? [];
    }
}