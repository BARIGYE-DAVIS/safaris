<?php

namespace App\Mail;

use App\Models\Subscribers;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSubscriberNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Subscribers $subscriber;

    public function __construct(Subscribers $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function build()
    {
        return $this->subject('New Subscriber Received')
                    ->view('emails.new-subscriber-notification')
                    ->with(['subscriber' => $this->subscriber]);
    }
}