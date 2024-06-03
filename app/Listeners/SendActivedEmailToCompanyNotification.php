<?php

namespace App\Listeners;

use App\Events\CompanyActivedEvent;
use App\Models\User\User;
use Illuminate\Support\Facades\Mail;

class SendActivedEmailToCompanyNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CompanyActivedEvent $event): void
    {
        if (is_null($event->user->email_verified_at)) {
            return;
        }
        $data = [
            'title' => 'Your account has been activated',
            'content' => 'Your Company account has been activated, now you can select your vehicles to receive requests and offers in your Company panel.',
            'button_url' => route('login'),
            'button_text' => 'Company Panel'
        ];

        Mail::send('vendor.emails.style1', $data, function ($message) use ($event) {
            $message->to($event->user->email)
                ->subject('Your account has been activated');
        });
    }
}
