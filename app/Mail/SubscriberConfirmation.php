<?php

namespace App\Mail;

use App\Models\Subscribers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Subscribers $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscribers $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Thanks for subscribing')
                    ->view('emails.subscriber-confirmation')
                    ->with(['subscriber' => $this->subscriber]);
    }
}