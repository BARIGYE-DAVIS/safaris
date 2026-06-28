<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVisit extends Model
{
    protected $fillable = [
        'url',
        'page_title',
        'ip_address',
        'country',
        'city',
        'user_agent',
        'referrer',
        'user_id',
        'session_id',
        'time_on_page',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}