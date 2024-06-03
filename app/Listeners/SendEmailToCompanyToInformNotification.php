<?php

namespace App\Listeners;

use App\Events\CompanyActivedEvent;
use App\Events\CompanyInformEvent;
use Illuminate\Support\Facades\Mail;

class SendEmailToCompanyToInformNotification
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
    public function handle(CompanyInformEvent $event): void
    {
        if (is_null($event->company->users->email_verified_at)) {
            return;
        }
        $data = [
            'title' => 'Rorex has a shipment request for you',
            'content' => 'Rorex has created a request list to send own products. You can go to the Company-Panel and watch the available list and you can also send your suggestions.',
            'button_url' => route('login'),
            'button_text' => 'Company Panel'
        ];

        Mail::send('vendor.emails.style1', $data, function ($message) use ($event) {
            $message->to($event->company->users->email)
                ->subject('Rorex has a shipment request for you');
        });
    }
}
