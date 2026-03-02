<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag_icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all destinations for this country
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class)->orderBy('sort_order');
    }

    /**
     * Get all activities available in this country
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_country')
                    ->withPivot('is_available')
                    ->withTimestamps();
    }

    /**
     * Scope: Get only active countries
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
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get active destinations count
     */
    public function getActiveDestinationsCountAttribute()
    {
        return $this->destinations()->where('is_active', true)->count();
    }

    
    /**
     * Get active activities count
     */
    public function getActiveActivitiesCountAttribute()
    {
        return $this->activities()->where('is_active', true)->count();
    }
}