<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'contact_messages';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'inquiry_type',
        'status',
        'ip_address',
        'user_agent',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'ip_address',
        'user_agent',
    ];

    /**
     * Inquiry types available
     */
    public const INQUIRY_TYPES = [
        'general' => 'General Inquiry',
        'tour_booking' => 'Tour Booking',
        'custom_safari' => 'Custom Safari',
        'group_booking' => 'Group Booking',
        'pricing' => 'Pricing Information',
        'technical_support' => 'Technical Support',
    ];

    /**
     * Status options available
     */
    public const STATUSES = [
        'pending' => 'Pending',
        'read' => 'Read',
        'replied' => 'Replied',
        'resolved' => 'Resolved',
        'spam' => 'Spam',
    ];

    /**
     * Status colors for UI
     */
    public const STATUS_COLORS = [
        'pending' => 'yellow',
        'read' => 'blue',
        'replied' => 'green',
        'resolved' => 'green',
        'spam' => 'red',
    ];

    /**
     * Get the inquiry type label
     */
    public function getInquiryTypeLabelAttribute()
    {
        return self::INQUIRY_TYPES[$this->inquiry_type] ?? ucfirst($this->inquiry_type);
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status color for UI
     */
    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    /**
     * Check if contact is unread
     */
    public function getIsUnreadAttribute()
    {
        return $this->status === 'pending' && is_null($this->read_at);
    }

    /**
     * Check if contact needs attention
     */
    public function getNeedsAttentionAttribute()
    {
        return in_array($this->status, ['pending', 'read']);
    }

    /**
     * Get truncated message for listing
     */
    public function getTruncatedMessageAttribute()
    {
        return \Str::limit($this->message, 150);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }

        // Basic phone formatting - customize based on your needs
        $phone = preg_replace('/[^0-9+]/', '', $this->phone);
        
        if (strlen($phone) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($phone, 0, 3), 
                substr($phone, 3, 3), 
                substr($phone, 6)
            );
        }
        
        return $this->phone;
    }

    /**
     * Get time since created (human readable)
     */
    public function getTimeSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get response time if replied
     */
    public function getResponseTimeAttribute()
    {
        if ($this->status === 'replied' && $this->read_at) {
            return $this->read_at->diffForHumans($this->created_at, true);
        }
        return null;
    }

    /**
     * Mark contact as read
     */
    public function markAsRead()
    {
        if ($this->status === 'pending') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
        return $this;
    }

    /**
     * Mark contact as replied
     */
    public function markAsReplied()
    {
        $this->update([
            'status' => 'replied',
            'read_at' => $this->read_at ?? now(),
        ]);
        return $this;
    }

    /**
     * Mark contact as resolved
     */
    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'read_at' => $this->read_at ?? now(),
        ]);
        return $this;
    }

    /**
     * Mark contact as spam
     */
    public function markAsSpam()
    {
        $this->update([
            'status' => 'spam',
        ]);
        return $this;
    }

    /**
     * Scope: Get unread contacts
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'pending')
                    ->whereNull('read_at');
    }

    /**
     * Scope: Get contacts needing attention
     */
    public function scopeNeedsAttention($query)
    {
        return $query->whereIn('status', ['pending', 'read']);
    }

    /**
     * Scope: Get contacts by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get contacts by inquiry type
     */
    public function scopeByInquiryType($query, $type)
    {
        return $query->where('inquiry_type', $type);
    }

    /**
     * Scope: Get recent contacts
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get contacts from this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope: Get contacts from this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope: Get contacts from today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Search contacts
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhere('message', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from && $to) {
            return $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay()
            ]);
        } elseif ($from) {
            return $query->where('created_at', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($to) {
            return $query->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        return $query;
    }

    /**
     * Get contact statistics
     */
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'pending' => self::where('status', 'pending')->count(),
            'read' => self::where('status', 'read')->count(),
            'replied' => self::where('status', 'replied')->count(),
            'resolved' => self::where('status', 'resolved')->count(),
            'spam' => self::where('status', 'spam')->count(),
            'unread' => self::unread()->count(),
            'needs_attention' => self::needsAttention()->count(),
            'this_month' => self::thisMonth()->count(),
            'this_week' => self::thisWeek()->count(),
            'today' => self::today()->count(),
            'avg_response_time' => self::where('status', 'replied')
                                      ->whereNotNull('read_at')
                                      ->get()
                                      ->avg(function ($contact) {
                                          return $contact->created_at->diffInHours($contact->read_at);
                                      }),
        ];
    }

    /**
     * Get inquiry type statistics
     */
    public static function getInquiryTypeStats()
    {
        return self::selectRaw('inquiry_type, COUNT(*) as count')
                   ->groupBy('inquiry_type')
                   ->pluck('count', 'inquiry_type')
                   ->toArray();
    }

    /**
     * Get monthly contact trends
     */
    public static function getMonthlyTrends($months = 12)
    {
        return self::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                   ->where('created_at', '>=', now()->subMonths($months))
                   ->groupBy('year', 'month')
                   ->orderBy('year')
                   ->orderBy('month')
                   ->get()
                   ->map(function ($item) {
                       return [
                           'date' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                           'count' => $item->count
                       ];
                   });
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set read_at when status changes to read or higher
        static::updating(function ($contact) {
            if ($contact->isDirty('status') && 
                in_array($contact->status, ['read', 'replied', 'resolved']) && 
                !$contact->read_at) {
                $contact->read_at = now();
            }
        });
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Convert to array for API responses
     */
    public function toApiArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'inquiry_type' => $this->inquiry_type,
            'inquiry_type_label' => $this->inquiry_type_label,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'is_unread' => $this->is_unread,
            'needs_attention' => $this->needs_attention,
            'time_since' => $this->time_since,
            'response_time' => $this->response_time,
            'created_at' => $this->created_at->toISOString(),
            'read_at' => $this->read_at?->toISOString(),
        ];
    }
}