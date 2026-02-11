<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTourRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'language',
        'travel_date_from',
        'travel_date_to',
        'flexible_dates',
        'duration',
        'adults_count',
        'children_count',
        'infants_count',
        'budget_category',
        'destinations',
        'activities',
        'accommodation_preference',
        'special_requirements',
        'dietary_restrictions',
        'medical_conditions',
        'special_requests',
        'heard_from',
        'approximate_budget',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'travel_date_from' => 'date',
        'travel_date_to' => 'date',
        'flexible_dates' => 'boolean',
        'adults_count' => 'integer',
        'children_count' => 'integer',
        'infants_count' => 'integer',
        'destinations' => 'array',
        'activities' => 'array',
        'special_requirements' => 'array',
    ];

    /**
     * Status constants
     */
    const STATUS_NEW = 'new';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_QUOTED = 'quoted';
    const STATUS_BOOKED = 'booked';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_QUOTED => 'Quoted',
            self::STATUS_BOOKED => 'Booked',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get new requests
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * Scope: Get pending requests (new + contacted)
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_CONTACTED]);
    }

    /**
     * Scope: Latest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Search by name or email
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Get selected destinations details
     */
    public function getDestinationsDetailsAttribute()
    {
        if (!$this->destinations) {
            return collect();
        }
        return Destination::whereIn('id', $this->destinations)->get();
    }

    /**
     * Get selected activities details
     */
    public function getActivitiesDetailsAttribute()
    {
        if (!$this->activities) {
            return collect();
        }
        return Activity::whereIn('id', $this->activities)->get();
    }

    /**
     * Get total travelers count
     */
    public function getTotalTravelersAttribute()
    {
        return $this->adults_count + $this->children_count + $this->infants_count;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_NEW => 'blue',
            self::STATUS_CONTACTED => 'yellow',
            self::STATUS_QUOTED => 'purple',
            self::STATUS_BOOKED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get reference number
     */
    public function getReferenceNumberAttribute()
    {
        return 'CTR-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted travel dates
     */
    public function getTravelDatesFormattedAttribute()
    {
        if ($this->travel_date_from && $this->travel_date_to) {
            return $this->travel_date_from->format('M d, Y') . ' - ' . $this->travel_date_to->format('M d, Y');
        }
        return 'Dates not specified';
    }

    /**
     * Get budget category details
     */
    public function getBudgetCategoryDetailsAttribute()
    {
        if ($this->budget_category) {
            return BudgetCategory::where('slug', $this->budget_category)->first();
        }
        return null;
    }



         
    public function getDestinationsListAttribute()
    {
        if (!$this->destinations || !is_array($this->destinations)) {
            return collect();
        }
        return \App\Models\Destination::whereIn('id', $this->destinations)
                    ->orderBy('name')
                    ->get();
    }
       public function getActivitiesListAttribute()
    {
        if (!$this->activities || !is_array($this->activities)) {
            return collect();
        }
        return \App\Models\Activity::whereIn('id', $this->activities)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Check if request is new
     */
    public function isNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return in_array($this->status, [self::STATUS_NEW, self::STATUS_CONTACTED]);
    }

    /**
     * Mark as contacted
     */
    public function markAsContacted()
    {
        $this->update(['status' => self::STATUS_CONTACTED]);
    }

    /**
     * Mark as quoted
     */
    public function markAsQuoted()
    {
        $this->update(['status' => self::STATUS_QUOTED]);
    }

    /**
     * Mark as booked
     */
    public function markAsBooked()
    {
        $this->update(['status' => self::STATUS_BOOKED]);
    }

    /**
     * Mark as cancelled
     */
    public function markAsCancelled()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
}