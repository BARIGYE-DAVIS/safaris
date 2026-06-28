<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ActivityOption extends Model
{
    protected $fillable = [
        'type',      // bring | included | excluded
        'name',      // option text
        'is_active', // true/false
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_activity_option')
            ->withTimestamps();
    }
}