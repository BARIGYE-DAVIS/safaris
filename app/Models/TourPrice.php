<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'group_size',
        'price',
    ];

    // Relationships

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function itineraries()
{
    return $this->hasMany(TourItinerary::class);
}
public function prices()
{
    return $this->hasMany(TourPrice::class);
}
public function images()
{
    return $this->hasMany(TourImage::class);
}
}