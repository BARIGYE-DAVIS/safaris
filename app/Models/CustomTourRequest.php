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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (Using Accessors since data is stored as JSON)
    |--------------------------------------------------------------------------
    */

    /**
     * Get selected destinations details (relationship-style)
     * This acts as a pseudo-relationship since destinations are stored as JSON array
     */
    public function destinationsDetails()
    {
        if (!$this->destinations || !is_array($this->destinations)) {
            return collect();
        }
        return Destination::whereIn('id', $this->destinations)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get selected activities details (relationship-style)
     * This acts as a pseudo-relationship since activities are stored as JSON array
     */
    public function activitiesDetails()
    {
        if (!$this->activities || !is_array($this->activities)) {
            return collect();
        }
        return Activity::whereIn('id', $this->activities)
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get the country relationship
     */
    public function countryRelation()
    {
        return Country::where('name', $this->country)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

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
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope: Active requests (not cancelled)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    /**
     * Get selected destinations details (accessor)
     */
    public function getDestinationsDetailsAttribute()
    {
        if (!$this->destinations || !is_array($this->destinations)) {
            return collect();
        }
        return Destination::whereIn('id', $this->destinations)->get();
    }

    /**
     * Get selected activities details (accessor)
     */
    public function getActivitiesDetailsAttribute()
    {
        if (!$this->activities || !is_array($this->activities)) {
            return collect();
        }
        return Activity::whereIn('id', $this->activities)->get();
    }

    /**
     * Get destinations list (for views)
     */
    public function getDestinationsListAttribute()
    {
        if (!$this->destinations || !is_array($this->destinations)) {
            return collect();
        }
        return Destination::whereIn('id', $this->destinations)
                    ->with('country')
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get activities list (for views)
     */
    public function getActivitiesListAttribute()
    {
        if (!$this->activities || !is_array($this->activities)) {
            return collect();
        }
        return Activity::whereIn('id', $this->activities)
                    ->with('category')
                    ->orderBy('name')
                    ->get();
    }

    /**
     * Get total travelers count
     */
    public function getTotalTravelersAttribute()
    {
        return ($this->adults_count ?? 0) + ($this->children_count ?? 0) + ($this->infants_count ?? 0);
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
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            self::STATUS_NEW => 'fa-star',
            self::STATUS_CONTACTED => 'fa-phone',
            self::STATUS_QUOTED => 'fa-file-invoice-dollar',
            self::STATUS_BOOKED => 'fa-check-circle',
            self::STATUS_CANCELLED => 'fa-times-circle',
            default => 'fa-question-circle',
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
        if ($this->travel_date_from) {
            return $this->travel_date_from->format('M d, Y') . ' onwards';
        }
        return 'Dates not specified';
    }

    /**
     * Get travel duration in days
     */
    public function getTravelDurationDaysAttribute()
    {
        if ($this->travel_date_from && $this->travel_date_to) {
            return $this->travel_date_from->diffInDays($this->travel_date_to) + 1;
        }
        return null;
    }

    /**
     * Get budget category details
     */
    public function getBudgetCategoryDetailsAttribute()
    {
        if ($this->budget_category && class_exists('App\Models\BudgetCategory')) {
            return \App\Models\BudgetCategory::where('slug', $this->budget_category)
                                            ->orWhere('name', $this->budget_category)
                                            ->first();
        }
        return null;
    }

    /**
     * Get budget display
     */
    public function getBudgetDisplayAttribute()
    {
        if ($this->approximate_budget) {
            return $this->approximate_budget;
        }
        if ($this->budget_category) {
            return ucfirst(str_replace('-', ' ', $this->budget_category));
        }
        return 'Not specified';
    }

    /**
     * Get destinations count
     */
    public function getDestinationsCountAttribute()
    {
        return is_array($this->destinations) ? count($this->destinations) : 0;
    }

    /**
     * Get activities count
     */
    public function getActivitiesCountAttribute()
    {
        return is_array($this->activities) ? count($this->activities) : 0;
    }

    /**
     * Get destinations names as comma-separated string
     */
    public function getDestinationsNamesAttribute()
    {
        return $this->destinations_list->pluck('name')->implode(', ') ?: 'None selected';
    }

    /**
     * Get activities names as comma-separated string
     */
    public function getActivitiesNamesAttribute()
    {
        return $this->activities_list->pluck('name')->implode(', ') ?: 'None selected';
    }

    /**
     * Get special requirements as comma-separated string
     */
    public function getSpecialRequirementsTextAttribute()
    {
        if (!$this->special_requirements || !is_array($this->special_requirements)) {
            return 'None';
        }
        return implode(', ', $this->special_requirements);
    }

    /**
     * Check if has special requirements
     */
    public function hasSpecialRequirements()
    {
        return is_array($this->special_requirements) && count($this->special_requirements) > 0;
    }

    /**
     * Check if has dietary restrictions
     */
    public function hasDietaryRestrictions()
    {
        return !empty($this->dietary_restrictions);
    }

    /**
     * Check if has medical conditions
     */
    public function hasMedicalConditions()
    {
        return !empty($this->medical_conditions);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECK METHODS
    |--------------------------------------------------------------------------
    */

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
     * Check if request is contacted
     */
    public function isContacted()
    {
        return $this->status === self::STATUS_CONTACTED;
    }

    /**
     * Check if request is quoted
     */
    public function isQuoted()
    {
        return $this->status === self::STATUS_QUOTED;
    }

    /**
     * Check if request is booked
     */
    public function isBooked()
    {
        return $this->status === self::STATUS_BOOKED;
    }

    /**
     * Check if request is cancelled
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS UPDATE METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mark as contacted
     */
    public function markAsContacted()
    {
        return $this->update(['status' => self::STATUS_CONTACTED]);
    }

    /**
     * Mark as quoted
     */
    public function markAsQuoted()
    {
        return $this->update(['status' => self::STATUS_QUOTED]);
    }

    /**
     * Mark as booked
     */
    public function markAsBooked()
    {
        return $this->update(['status' => self::STATUS_BOOKED]);
    }

    /**
     * Mark as cancelled
     */
    public function markAsCancelled()
    {
        return $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /*
    |--------------------------------------------------------------------------
    | UTILITY METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Add admin note
     */
    public function addAdminNote($note)
    {
        $existingNotes = $this->admin_notes ?? '';
        $timestamp = now()->format('Y-m-d H:i:s');
        $newNote = "\n\n[{$timestamp}]\n" . $note;

        return $this->update([
            'admin_notes' => $existingNotes . $newNote
        ]);
    }

    /**
     * Get formatted admin notes (split by timestamp)
     */
    public function getFormattedAdminNotesAttribute()
    {
        if (!$this->admin_notes) {
            return [];
        }

        // Split notes by timestamp pattern
        $notes = preg_split('/\n\n\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\n/', $this->admin_notes, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $formatted = [];
        for ($i = 1; $i < count($notes); $i += 2) {
            if (isset($notes[$i]) && isset($notes[$i + 1])) {
                $formatted[] = [
                    'timestamp' => $notes[$i],
                    'note' => trim($notes[$i + 1])
                ];
            }
        }

        return $formatted;
    }

    /**
     * Check if request has destinations
     */
    public function hasDestinations()
    {
        return is_array($this->destinations) && count($this->destinations) > 0;
    }

    /**
     * Check if request has activities
     */
    public function hasActivities()
    {
        return is_array($this->activities) && count($this->activities) > 0;
    }

    /**
     * Get age display
     */
    public function getAgeDisplayAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if request is urgent (created more than 48 hours ago and still new)
     */
    public function isUrgent()
    {
        return $this->isNew() && $this->created_at->diffInHours(now()) > 48;
    }
}