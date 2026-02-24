<?php

namespace App\Http\Controllers;

use App\Models\Subscribers;
use App\Mail\SubscriberConfirmation;
use App\Mail\NewSubscriberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SubscribersController extends Controller
{
    /**
     * Display a paginated listing of subscribers for admin.
     */
    public function index(Request $request)
    {
        $subscribers = Subscribers::orderBy('created_at', 'desc')->paginate(25);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    /**
     * Store a newly created subscriber.
     * Sends confirmation mail to subscriber and notification to admin when a new record is created.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($data['email']));

        // Check for existing email first — do not create duplicates
        $existing = Subscribers::where('email', $email)->first();

        if ($existing) {
            $message = 'This email address is already subscribed.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'exists' => true], 200);
            }
            return redirect()->back()->with('info', $message);
        }

        // Create new subscriber
        try {
            $subscriber = Subscribers::create(['email' => $email]);
        } catch (\Throwable $e) {
            Log::error('Failed to create subscriber: ' . $e->getMessage(), ['email' => $email]);
            $message = 'An error occurred while subscribing. Please try again later.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }

        // Send emails (best-effort). Use try/catch to avoid breaking UX on mail failures.
        try {
            Mail::to($subscriber->email)->send(new SubscriberConfirmation($subscriber));
        } catch (\Throwable $e) {
            Log::error('Subscriber confirmation email failed: ' . $e->getMessage(), ['email' => $subscriber->email]);
        }

        try {
            $adminEmail = config('mail.admin_address', env('MAIL_ADMIN_EMAIL', null));
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewSubscriberNotification($subscriber));
            } else {
                Log::warning('ADMIN_EMAIL not set. Skipping admin notification for new subscriber.', ['email' => $subscriber->email]);
            }
        } catch (\Throwable $e) {
            Log::error('New subscriber admin notification failed: ' . $e->getMessage(), ['email' => $subscriber->email]);
        }

        $message = 'Thank you for subscribing! A confirmation email has been sent.';

        if ($request->wantsJson()) {
            return response()->json(['message' => $message, 'exists' => false], 201);
        }

        return redirect()->back()->with('success', $message);
    }
}