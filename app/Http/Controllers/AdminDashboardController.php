<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // 1. KEY METRICS (Quick Stats)
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', Booking::STATUS_CONFIRMED)
            ->sum('total_cost') ?? 0;
        $pendingApprovals = Booking::where('status', Booking::STATUS_PENDING)->count();
        $activeTours = Tour::where('status', 'published')->count();

        // Calculate percentage changes (compare with last 30 days)
        $bookingsLastMonth = Booking::whereBetween('created_at', [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth()
        ])->count();

        $bookingsThisMonth = Booking::whereBetween('created_at', [
            Carbon::now()->subMonth(),
            Carbon::now()
        ])->count();

        $bookingsChange = $bookingsLastMonth > 0
            ? (($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth) * 100
            : 0;

        // 2. REVENUE CHART DATA (Last 12 months)
        $revenueData = $this->getRevenueChartData();

        // 3. BOOKINGS OVERVIEW CHART (Status breakdown)
        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // 4. RECENT BOOKINGS (Last 10)
        $recentBookings = Booking::with('tour')
            ->latest()
            ->limit(10)
            ->get();

        // 5. PENDING ACTIONS
        $pendingBookings = Booking::where('status', Booking::STATUS_PENDING)
            ->with('tour')
            ->latest()
            ->limit(5)
            ->get();

        // 6. POPULAR SAFARI PACKAGES (Top 5)
        $popularTours = Tour::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($tour) {
                $revenue = Booking::where('tour_id', $tour->id)
                    ->where('status', Booking::STATUS_CONFIRMED)
                    ->sum('total_cost') ?? 0;

                $tour->total_revenue = $revenue;
                return $tour;
            });

        // 7. UPCOMING SAFARIS (Next 30 days)
        $upcomingSafaris = Booking::where('travel_date', '>=', Carbon::today())
            ->where('travel_date', '<=', Carbon::today()->addDays(30))
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->with('tour')
            ->orderBy('travel_date')
            ->get()
            ->groupBy(function ($booking) {
                return $booking->travel_date->format('Y-m-d');
            });

        // 8. CUSTOMER STATISTICS
        $totalCustomers = Booking::distinct('email')->count('email');
        $newCustomersThisWeek = Booking::where('created_at', '>=', Carbon::now()->startOfWeek())
            ->distinct('email')
            ->count('email');

        // 9. TOP CUSTOMERS (by bookings)
        $topCustomers = Booking::select('name', 'email', DB::raw('COUNT(*) as booking_count'), DB::raw('SUM(total_cost) as total_spent'))
            ->groupBy('email', 'name')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // 10. MONTHLY REVENUE (This year)
        $monthlyRevenue = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_cost) as revenue')
            )
            ->where('status', Booking::STATUS_CONFIRMED)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('revenue', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyRevenueComplete = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueComplete[] = $monthlyRevenue[$i] ?? 0;
        }

        // 11. NOTIFICATIONS/ALERTS
        $notifications = $this->getAdminNotifications();

        // 12. SYSTEM STATUS — safe version for shared hosting
        $systemStatus = [
            'database' => $this->checkDatabaseStatus(),
            'storage'  => $this->getStorageUsage(),
            'last_backup' => $this->getLastBackupTime(),
        ];

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'pendingApprovals',
            'activeTours',
            'bookingsChange',
            'revenueData',
            'bookingsByStatus',
            'recentBookings',
            'pendingBookings',
            'popularTours',
            'upcomingSafaris',
            'totalCustomers',
            'newCustomersThisWeek',
            'topCustomers',
            'monthlyRevenueComplete',
            'notifications',
            'systemStatus'
        ));
    }

    /**
     * Get revenue chart data for the last 12 months
     */
    private function getRevenueChartData()
    {
        $monthlyData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $revenue = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', Booking::STATUS_CONFIRMED)
                ->sum('total_cost') ?? 0;

            $monthlyData[] = [
                'month'   => $date->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        return $monthlyData;
    }

    /**
     * Get admin notifications
     */
    private function getAdminNotifications()
    {
        $notifications = [];

        // New bookings today
        $newBookingsToday = Booking::whereDate('created_at', Carbon::today())->count();
        if ($newBookingsToday > 0) {
            $notifications[] = [
                'type'    => 'success',
                'icon'    => 'bell',
                'message' => "{$newBookingsToday} new booking(s) received today",
                'time'    => 'Today',
            ];
        }

        // Pending approvals
        $pendingCount = Booking::where('status', Booking::STATUS_PENDING)->count();
        if ($pendingCount > 0) {
            $notifications[] = [
                'type'    => 'warning',
                'icon'    => 'alert-circle',
                'message' => "{$pendingCount} booking(s) awaiting approval",
                'time'    => 'Now',
            ];
        }

        // Upcoming safaris (next 7 days)
        $upcomingCount = Booking::where('travel_date', '>=', Carbon::today())
            ->where('travel_date', '<=', Carbon::today()->addDays(7))
            ->where('status', Booking::STATUS_CONFIRMED)
            ->count();

        if ($upcomingCount > 0) {
            $notifications[] = [
                'type'    => 'info',
                'icon'    => 'calendar',
                'message' => "{$upcomingCount} safari(s) starting in the next 7 days",
                'time'    => 'This week',
            ];
        }

        // Tours without images
        $toursWithoutImages = Tour::doesntHave('images')
            ->where('status', 'published')
            ->count();

        if ($toursWithoutImages > 0) {
            $notifications[] = [
                'type'    => 'warning',
                'icon'    => 'image',
                'message' => "{$toursWithoutImages} published tour(s) missing images",
                'time'    => 'Action needed',
            ];
        }

        return $notifications;
    }

    /**
     * Check database connectivity — safe for shared hosting
     */
    private function checkDatabaseStatus()
    {
        try {
            DB::select('SELECT 1');
            return 'Connected';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    /**
     * Get storage usage — safe version for shared/InfinityFree hosting.
     * disk_total_space() and disk_free_space() are disabled on most shared hosts.
     */
    private function getStorageUsage()
    {
        // disk_total_space() and disk_free_space() are disabled on InfinityFree
        // and most shared hosting environments — return N/A to avoid fatal errors
        return 'N/A';
    }

    /**
     * Get last backup time (placeholder)
     */
    private function getLastBackupTime()
    {
        return 'Not configured';
    }

    /**
     * Get bookings filtered by date range
     */
    public function getBookingsByDateRange(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with('tour')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $bookings,
        ]);
    }

    /**
     * Get revenue statistics
     */
    public function getRevenueStats(Request $request)
    {
        $period = $request->input('period', 'month'); // day, week, month, year

        switch ($period) {
            case 'day':
                $revenueData = $this->getDailyRevenue();
                break;
            case 'week':
                $revenueData = $this->getWeeklyRevenue();
                break;
            case 'year':
                $revenueData = $this->getYearlyRevenue();
                break;
            default:
                $revenueData = $this->getMonthlyRevenue();
        }

        return response()->json([
            'success' => true,
            'data'    => $revenueData,
        ]);
    }

    private function getDailyRevenue()
    {
        return Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_cost) as revenue')
            )
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();
    }

    private function getWeeklyRevenue()
    {
        // Use YEARWEEK() — supported on MySQL 5.x used by InfinityFree
        return Booking::select(
                DB::raw('YEARWEEK(created_at, 1) as week'),
                DB::raw('SUM(total_cost) as revenue')
            )
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('created_at', '>=', Carbon::now()->subWeeks(12))
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->get();
    }

    private function getMonthlyRevenue()
    {
        return Booking::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_cost) as revenue')
            )
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->get();
    }

    private function getYearlyRevenue()
    {
        return Booking::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_cost) as revenue')
            )
            ->where('status', Booking::STATUS_CONFIRMED)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->get();
    }

    /**
     * Export dashboard data
     */
    public function exportData(Request $request)
    {
        $type = $request->input('type', 'bookings');

        switch ($type) {
            case 'revenue':
                return $this->exportRevenue();
            case 'customers':
                return $this->exportCustomers();
            default:
                return $this->exportBookings();
        }
    }

    private function exportBookings()
    {
        return response()->json(['message' => 'Bookings export - to be implemented']);
    }

    private function exportRevenue()
    {
        return response()->json(['message' => 'Revenue export - to be implemented']);
    }

    private function exportCustomers()
    {
        return response()->json(['message' => 'Customers export - to be implemented']);
    }
}