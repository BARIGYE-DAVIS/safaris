<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourItinerary extends Model
{
    use HasFactory;

    protected $table = 'tour_itinerary';

    protected $fillable = [
        'tour_id',
        'day_number',
        'day_title',
        'activity',
        'accommodation',
        'meals',
    ];

    // Relationships

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}