<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;

class MailServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only run if mail is properly configured
        if (config('mail.default') && config('mail.from.address')) {
            try {
                // Listen to the MessageSent event
                Event::listen(MessageSent::class, function (MessageSent $event) {
                    Log::info('Email sent successfully', [
                        'to' => collect($event->message->getTo())->keys()->first(),
                        'subject' => $event->message->getSubject(),
                    ]);
                });

            } catch (\Exception $e) {
                // Silently skip if mail isn't ready yet
                Log::debug('Mail logging skipped: ' . $e->getMessage());
            }
        }
    }
}