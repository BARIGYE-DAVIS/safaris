<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
public function store(Request $request)
{
    try {
        // Validate the request - message is optional
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'group_size' => 'required|string|max:255',
            'travel_date' => 'required|date|after_or_equal:today',
            'message' => 'nullable|string|max:1000' // ← NULLABLE = OPTIONAL
        ]);

        // Get the tour and calculate total cost
        $tour = Tour::with('prices')->findOrFail($validated['tour_id']);
        $totalCost = $this->calculateTotalCost($tour, $validated['group_size']);

        // Create the booking
        $booking = Booking::create([
            'tour_id' => $validated['tour_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'whatsapp' => $validated['whatsapp'],
            'group_size' => $validated['group_size'],
            'travel_date' => $validated['travel_date'],
            'message' => $validated['message'], // Will be null if not provided
            'total_cost' => $totalCost,
            'status' => Booking::STATUS_PENDING
        ]);

        // Send notification emails (in background)
        try {
            $this->sendBookingNotifications($booking);
        } catch (\Exception $e) {
            // Don't fail the booking if emails fail
            Log::warning('Email sending failed but booking saved: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking request submitted successfully!',
            'booking_id' => $booking->id
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Please check your input.',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        Log::error('Booking creation failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Please try again or contact us directly.'
        ], 500);
    }
}
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

    private function generateWhatsAppUrl(Booking $booking)
    {
        $message = "Hi! I just submitted a booking request for '{$booking->tour->title}' tour.\n\n";
        $message .= "Booking Details:\n";
        $message .= "Name: {$booking->name}\n";
        $message .= "Email: {$booking->email}\n";
        $message .= "Group Size: {$booking->group_size}\n";
        $message .= "Travel Date: " . $booking->travel_date->format('F j, Y') . "\n";
        
        if ($booking->total_cost) {
            $message .= "Estimated Cost: $" . number_format($booking->total_cost) . "\n";
        }
        
        if ($booking->message) {
            $message .= "Additional Requirements: {$booking->message}\n";
        }
        
        $message .= "\nBooking ID: #{$booking->id}";

        return "https://wa.me/256700000000?text=" . urlencode($message);
    }

 private function sendBookingNotifications(Booking $booking)
{
    try {
        // Send confirmation email to customer
        Mail::send('emails.booking-confirmation', compact('booking'), function ($message) use ($booking) {
            $message->to($booking->email, $booking->name)
                    ->subject('Booking Confirmation - ' . $booking->tour->title);
            $message->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Send notification email to admin (YOU)
        Mail::send('emails.booking-notification', compact('booking'), function ($message) use ($booking) {
            $message->to(config('mail.admin_email'), config('mail.admin_name'))  // ← Changed this
                    ->subject('🚨 New Booking Request - ' . $booking->tour->title);
            $message->from(config('mail.from.address'), config('mail.from.name'));
        });

    } catch (\Exception $e) {
        Log::error('Failed to send booking emails: ' . $e->getMessage());
        // Don't fail the booking if emails fail
    }
}

    public function show(Booking $booking)
{
    $booking->load('tour');
    return view('booking.show', compact('booking')); // ← FIXED: singular view path
}

// Also fix the admin index method:
public function index(Request $request)
{
    $query = Booking::with('tour')->latest();

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->filled('from_date')) {
        $query->whereDate('travel_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('travel_date', '<=', $request->to_date);
    }

    // Search by name or email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $bookings = $query->paginate(20);

    return view('admin.booking.index', compact('bookings')); // ← FIXED: should be 'bookings' not 'booking'
}
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully!'
        ]);
    }
}