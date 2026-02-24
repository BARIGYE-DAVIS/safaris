<?php

namespace App\Http\Controllers;

use App\Mail\AdminGenericMessageMail;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\CustomTourRequest;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminEmailController extends Controller
{
    /**
     * Compose form with selectable existing recipients.
     */
    public function compose()
    {
        // Keep lists light for UI performance; adjust limits as needed.
        $subscribers = Subscribers::query()
            ->select('id', 'email', 'created_at')
            ->latest()
            ->limit(200)
            ->get();

        $bookings = Booking::query()
            ->select('id', 'name', 'email', 'created_at', 'status')
            ->latest()
            ->limit(200)
            ->get();

        $contacts = ContactMessage::query()
            ->select('id', 'name', 'email', 'created_at', 'subject')
            ->latest()
            ->limit(200)
            ->get();

        $tourRequests = CustomTourRequest::query()
            ->select('id', 'name', 'email', 'created_at', 'status')
            ->latest()
            ->limit(200)
            ->get();

        return view('admin.emails.compose', compact('subscribers', 'bookings', 'contacts', 'tourRequests'));
    }

    /**
     * Send email to manual or existing recipient.
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipient_mode' => ['required', Rule::in(['manual', 'existing'])],

            // manual
            'to_email' => ['nullable', 'email', 'required_if:recipient_mode,manual'],

            // existing
            'existing_source' => ['nullable', Rule::in(['subscribers', 'bookings', 'contacts', 'tour_requests']), 'required_if:recipient_mode,existing'],
            'existing_id' => ['nullable', 'integer', 'required_if:recipient_mode,existing'],

            // message
            'subject' => ['required', 'string', 'max:190'],
            'body' => ['required', 'string'],
            'greeting' => ['nullable', 'string', 'max:190'],
            'signature' => ['nullable', 'string', 'max:500'],
            'action_text' => ['nullable', 'string', 'max:60'],
            'action_url' => ['nullable', 'url', 'max:500'],

            // branding overrides (optional)
            'brand_name' => ['nullable', 'string', 'max:120'],
            'brand_color' => ['nullable', 'string', 'max:20'],
            'preheader' => ['nullable', 'string', 'max:190'],
            'footer_note' => ['nullable', 'string', 'max:190'],
        ]);

        $toEmail = null;

        if ($request->recipient_mode === 'manual') {
            $toEmail = $request->to_email;
        } else {
            $toEmail = $this->resolveExistingEmail($request->existing_source, (int) $request->existing_id);
        }

        Mail::to($toEmail)->send(new AdminGenericMessageMail([
            'subject' => $request->subject,
            'preheader' => $request->preheader,
            'brandName' => $request->brand_name,
            'brandColor' => $request->brand_color,
            'footerNote' => $request->footer_note,

            'greeting' => $request->greeting,
            'body' => $request->body,
            'actionText' => $request->action_text,
            'actionUrl' => $request->action_url,
            'signature' => $request->signature,
        ]));

        return back()->with('status', "Email sent to {$toEmail}");
    }

    private function resolveExistingEmail(string $source, int $id): string
    {
        return match ($source) {
            'subscribers' => (string) Subscribers::query()->findOrFail($id)->email,
            'bookings' => (string) Booking::query()->findOrFail($id)->email,
            'contacts' => (string) ContactMessage::query()->findOrFail($id)->email,
            'tour_requests' => (string) CustomTourRequest::query()->findOrFail($id)->email,
            default => abort(422, 'Invalid recipient source'),
        };
    }
}