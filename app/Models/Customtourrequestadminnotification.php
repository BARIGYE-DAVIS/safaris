<?php

namespace App\Mail;

use App\Models\CustomTourRequest;
use App\Models\Destination;
use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomTourRequestAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public CustomTourRequest $tourRequest;
    public $destinations;
    public $activities;

    /**
     * Create a new message instance.
     */
    public function __construct(CustomTourRequest $tourRequest)
    {
        $this->tourRequest = $tourRequest;

        // Load destination models
        $this->destinations = collect();
        if (!empty($tourRequest->destinations) && is_array($tourRequest->destinations)) {
            $this->destinations = Destination::whereIn('id', $tourRequest->destinations)
                ->with('country')
                ->get();
        }

        // Load activity models
        $this->activities = collect();
        if (!empty($tourRequest->activities) && is_array($tourRequest->activities)) {
            $this->activities = Activity::whereIn('id', $tourRequest->activities)->get();
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🗺️ New Custom Safari Request — ' . $this->tourRequest->reference_number . ' | ' . $this->tourRequest->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Place this file at: resources/views/emails/custom-tour-notification-admin.blade.php
            view: 'emails.custom-tour-notification',
            with: [
                'tourRequest'  => $this->tourRequest,
                'destinations' => $this->destinations,
                'activities'   => $this->activities,
            ],
        );
    }
}