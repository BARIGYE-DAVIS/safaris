<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the contact form
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'country' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
                'phone' => 'nullable|string|max:20',
            ], [
                'name.required' => 'Please enter your full name.',
                'name.max' => 'Name cannot exceed 255 characters.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'country.required' => 'Please select your country.',
                'country.max' => 'Country cannot exceed 255 characters.',
                'phone.max' => 'Phone number cannot exceed 20 characters.',
                'subject.required' => 'Please enter a subject for your message.',
                'subject.max' => 'Subject cannot exceed 255 characters.',
                'message.required' => 'Please enter your message.',
                'message.max' => 'Message cannot exceed 5000 characters.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the errors below and try again.');
            }

            // Create the contact message record
            $contactMessage = ContactMessage::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'country' => $request->input('country'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'phone' => $request->input('phone'),
                'is_read' => false,
            ]);

            // Send notification email to ADMIN ONLY
            try {
                $adminEmail = config('mail.admin_email', 'admin@calmafricasafaris.com');

                Mail::send('emails.contact-notification', [
                    'contact' => $contactMessage
                ], function ($message) use ($contactMessage, $adminEmail) {
                    $message->to($adminEmail)
                        ->subject('New Contact Message: ' . $contactMessage->subject)
                        ->replyTo($contactMessage->email, $contactMessage->name);
                });
            } catch (\Exception $emailError) {
                // Log email error but don't fail the contact submission
                Log::error('Contact form admin email failed: ' . $emailError->getMessage());
            }

            // Return success response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your message! We\'ll get back to you within 24 hours.',
                    'contact_id' => $contactMessage->id
                ]);
            }

            return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again or contact us directly.',
                    'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again or contact us directly.');
        }
    }

    /**
     * Show success page after contact form submission
     */
    public function success()
    {
        return view('contact.success');
    }

    /**
     * Admin: Display all contact messages (requires admin middleware)
     */
    public function admin()
    {
        $contacts = ContactMessage::latest()
                                 ->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Admin: Show specific contact message
     */
    public function show($id)
    {
        $contact = ContactMessage::findOrFail($id);

        // Mark as read if not already
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Admin: Update read status
     */
    public function updateReadStatus(Request $request, $id)
    {
        $contact = ContactMessage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'is_read' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $contact->update([
            'is_read' => $request->is_read
        ]);

        return back()->with('success', 'Contact message status updated successfully.');
    }

    /**
     * Admin: Delete contact message
     */
    public function destroy($id)
    {
        $contact = ContactMessage::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Contact message deleted successfully.');
    }

    /**
     * Get contact statistics (for admin dashboard)
     */
    public function getStats()
    {
        return [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'read' => ContactMessage::where('is_read', true)->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
        ];
    }

    /**
     * Mark multiple contacts as read (bulk action)
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contact_messages,id',
            'action' => 'required|in:mark_read,mark_unread,delete'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $contactIds = $request->contact_ids;

        switch ($request->action) {
            case 'mark_read':
                ContactMessage::whereIn('id', $contactIds)->update(['is_read' => true]);
                $message = 'Selected messages marked as read.';
                break;

            case 'mark_unread':
                ContactMessage::whereIn('id', $contactIds)->update(['is_read' => false]);
                $message = 'Selected messages marked as unread.';
                break;

            case 'delete':
                ContactMessage::whereIn('id', $contactIds)->delete();
                $message = 'Selected messages deleted.';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Export contact messages to CSV (for admin)
     */
    public function export(Request $request)
    {
        $query = ContactMessage::query();

        // Apply filters if provided
        if ($request->filled('is_read')) {
            $query->where('is_read', $request->is_read);
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

        $contacts = $query->latest()->get();

        $filename = 'contact-messages-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Country', 'Phone', 'Subject',
                'Message', 'Is Read', 'Created At'
            ]);

            // CSV data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->country,
                    $contact->phone,
                    $contact->subject,
                    $contact->message,
                    $contact->is_read ? 'Yes' : 'No',
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get countries with message counts
     */
    public function getCountryStats()
    {
        return ContactMessage::selectRaw('country, COUNT(*) as count')
                            ->groupBy('country')
                            ->orderBy('count', 'desc')
                            ->pluck('count', 'country')
                            ->toArray();
    }

    /**
     * Search contact messages
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $contacts = ContactMessage::where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('subject', 'like', "%{$search}%")
                                 ->orWhere('message', 'like', "%{$search}%")
                                 ->orWhere('country', 'like', "%{$search}%")
                                 ->latest()
                                 ->paginate(20);

        return view('admin.contacts.index', compact('contacts', 'search'));
    }
}