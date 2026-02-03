<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking'; // ← SPECIFY SINGULAR TABLE NAME

    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'country',
        'whatsapp',
        'group_size',
        'travel_date',
        'message',
        'total_cost',
        'status'
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_cost' => 'decimal:2'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}