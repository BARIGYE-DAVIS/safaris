<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 
use Carbon\Carbon;

class ContactMessage extends Model
{
    use HasFactory;

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
        'country',
        'subject',
        'message',
        'phone',
        'is_read',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if message is unread
     */
    public function getIsUnreadAttribute()
    {
        return !$this->is_read;
    }

    /**
     * Get truncated message for listing
     */
    public function getTruncatedMessageAttribute()
    {
        return Str::limit($this->message, 150);
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
     * Get read status label
     */
    public function getReadStatusLabelAttribute()
    {
        return $this->is_read ? 'Read' : 'Unread';
    }

    /**
     * Get read status color
     */
    public function getReadStatusColorAttribute()
    {
        return $this->is_read ? 'green' : 'yellow';
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
        return $this;
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
        return $this;
    }

    /**
     * Scope: Get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Get read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope: Get messages by country
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope: Get recent messages
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get messages from this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope: Get messages from this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope: Get messages from today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Search messages
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
              ->orWhere('message', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%");
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
     * Get contact message statistics
     */
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'unread' => self::where('is_read', false)->count(),
            'read' => self::where('is_read', true)->count(),
            'this_month' => self::thisMonth()->count(),
            'this_week' => self::thisWeek()->count(),
            'today' => self::today()->count(),
        ];
    }

    /**
     * Get country statistics
     */
    public static function getCountryStats()
    {
        return self::selectRaw('country, COUNT(*) as count')
                   ->groupBy('country')
                   ->orderBy('count', 'desc')
                   ->pluck('count', 'country')
                   ->toArray();
    }

    /**
     * Get monthly message trends
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
            'country' => $this->country,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'is_read' => $this->is_read,
            'is_unread' => $this->is_unread,
            'read_status_label' => $this->read_status_label,
            'read_status_color' => $this->read_status_color,
            'time_since' => $this->time_since,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}