<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display booking form
     */
    public function create()
    {
        $tours = Tour::with('prices')->where('status', 'active')->get();
        return view('booking.create', compact('tours'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        try {
            // Validate the request - message is optional
            $validated = $request->validate([
                'tour_id' => 'nullable|exists:tours,id', // Nullable for custom safaris
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'country' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'group_size' => 'required|string|max:255',
                'travel_date' => 'required|date|after_or_equal:today',
                'message' => 'nullable|string|max:1000' // Optional
            ]);

            // Get the tour if specified
            $tour = null;
            $totalCost = null;
            if ($validated['tour_id']) {
                $tour = Tour::with('prices')->findOrFail($validated['tour_id']);
                $totalCost = $this->calculateTotalCost($tour, $validated['group_size']);
            }

            // Create the booking
            $booking = Booking::create([
                'tour_id' => $validated['tour_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'country' => $validated['country'],
                'whatsapp' => $validated['whatsapp'],
                'group_size' => $validated['group_size'],
                'travel_date' => $validated['travel_date'],
                'message' => $validated['message'],
                'total_cost' => $totalCost,
                'status' => 'pending'
            ]);

            // Send notification emails (in background)
            try {
                $this->sendBookingNotifications($booking);
            } catch (\Exception $e) {
                // Don't fail the booking if emails fail
                Log::warning('Email sending failed but booking saved: ' . $e->getMessage());
            }

            // FIXED: Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking request submitted successfully!',
                    'booking_id' => $booking->id
                ], 200); // Explicitly set 200 status code
            }

            return redirect()->route('booking.success')->with('booking_id', $booking->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your input.',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please try again or contact us directly.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    /**
     * Show booking success page
     */
    public function success()
    {
        return view('booking.success');
    }

    /**
     * Admin: Display all bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with('tour')->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('tour', function($tourQuery) use ($search) {
                      $tourQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Filter by travel date
        if ($request->filled('travel_date')) {
            $query->whereDate('travel_date', $request->travel_date);
        }

        // Filter by date range (created_at)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Admin: Show specific booking
     */
    public function show($id)
    {
        $booking = Booking::with('tour')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Admin: Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        $booking->update([
            'status' => $request->status
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully!'
            ]);
        }

        return back()->with('success', 'Booking status updated successfully!');
    }

    /**
     * Admin: Delete booking
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }

    /**
     * Admin: Bulk update bookings
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
            'action' => 'required|in:confirm,cancel,delete,complete'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $bookingIds = $request->booking_ids;

        switch ($request->action) {
            case 'confirm':
                Booking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);
                $message = 'Selected bookings confirmed successfully.';
                break;

            case 'cancel':
                Booking::whereIn('id', $bookingIds)->update(['status' => 'cancelled']);
                $message = 'Selected bookings cancelled successfully.';
                break;

            case 'complete':
                Booking::whereIn('id', $bookingIds)->update(['status' => 'completed']);
                $message = 'Selected bookings marked as completed.';
                break;

            case 'delete':
                Booking::whereIn('id', $bookingIds)->delete();
                $message = 'Selected bookings deleted successfully.';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Admin: Export bookings to CSV
     */
    public function export(Request $request)
    {
        $query = Booking::with('tour');

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->latest()->get();

        $filename = 'safari-bookings-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Customer Name', 'Email', 'Country', 'WhatsApp', 
                'Tour', 'Group Size', 'Travel Date', 'Total Cost', 
                'Status', 'Message', 'Booking Date'
            ]);

            // CSV data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->name,
                    $booking->email,
                    $booking->country,
                    $booking->whatsapp,
                    $booking->tour ? $booking->tour->title : 'Custom Safari',
                    $booking->group_size,
                    $booking->travel_date,
                    $booking->total_cost ? '$' . number_format($booking->total_cost) : 'TBD',
                    ucfirst($booking->status),
                    $booking->message,
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get booking statistics
     */
    public function getStats()
    {
        return [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'this_month' => Booking::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count(),
            'this_week' => Booking::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => Booking::whereDate('created_at', today())->count(),
            'total_revenue' => Booking::where('status', 'confirmed')
                                    ->whereNotNull('total_cost')
                                    ->sum('total_cost'),
        ];
    }

    /**
     * Calculate total cost based on tour and group size
     */
    private function calculateTotalCost(Tour $tour, string $groupSize)
    {
        // Find the matching price
        $price = $tour->prices->where('group_size', $groupSize)->first();
        
        if (!$price) {
            return null;
        }

        // Extract number from group size if possible
        preg_match('/\d+/', $groupSize, $matches);
        $numberOfPeople = isset($matches[0]) ? (int)$matches[0] : 1;

        return $price->price * $numberOfPeople;
    }

    /**
     * Generate WhatsApp URL with booking details
     */
    private function generateWhatsAppUrl(Booking $booking)
    {
        $tourName = $booking->tour ? $booking->tour->title : 'Custom Safari';
        
        $message = "Hi! I just submitted a booking request for '{$tourName}' tour.\n\n";
        $message .= "Booking Details:\n";
        $message .= "Name: {$booking->name}\n";
        $message .= "Email: {$booking->email}\n";
        $message .= "Group Size: {$booking->group_size}\n";
        $message .= "Travel Date: " . Carbon::parse($booking->travel_date)->format('F j, Y') . "\n";
        
        if ($booking->total_cost) {
            $message .= "Estimated Cost: $" . number_format($booking->total_cost) . "\n";
        }
        
        if ($booking->message) {
            $message .= "Additional Requirements: {$booking->message}\n";
        }
        
        $message .= "\nBooking ID: #{$booking->id}";

        return "https://wa.me/256752088768?text=" . urlencode($message);
    }

    /**
     * Send booking notification emails
     */
    private function sendBookingNotifications(Booking $booking)
    {
        try {
            // Send confirmation email to customer
            Mail::send('emails.booking-confirmation', compact('booking'), function ($message) use ($booking) {
                $tourName = $booking->tour ? $booking->tour->title : 'Custom Safari';
                $message->to($booking->email, $booking->name)
                        ->subject('Booking Confirmation - ' . $tourName);
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            // Send notification email to admin
            Mail::send('emails.booking-notification', compact('booking'), function ($message) use ($booking) {
                $tourName = $booking->tour ? $booking->tour->title : 'Custom Safari';
                $message->to(config('mail.admin_email', 'admin@calmafricasafaris.com'), 'Safari Admin')
                        ->subject('🚨 New Booking Request - ' . $tourName);
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error('Failed to send booking emails: ' . $e->getMessage());
            // Don't fail the booking if emails fail
        }
    }

    /**
     * Get country statistics for bookings
     */
    public function getCountryStats()
    {
        return Booking::selectRaw('country, COUNT(*) as count')
                     ->groupBy('country')
                     ->orderBy('count', 'desc')
                     ->pluck('count', 'country')
                     ->toArray();
    }

    /**
     * Get monthly booking trends
     */
    public function getMonthlyTrends($months = 12)
    {
        return Booking::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
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
}