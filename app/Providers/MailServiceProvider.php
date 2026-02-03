<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
                // Modern Laravel uses 'sent' instead of 'sending'
                Mail::sent(function ($message) {
                    Log::info('Email sent successfully', [
                        'to' => collect($message->getTo())->keys()->first(),
                        'subject' => $message->getSubject(),
                    ]);
                });

            } catch (\Exception $e) {
                // Silently skip if mail isn't ready yet
                Log::debug('Mail logging skipped: ' . $e->getMessage());
            }
        }
    }
}