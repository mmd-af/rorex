<?php

namespace App\Listeners;

use App\Events\CompanyRegistered;
use App\Models\User\User;
use Illuminate\Support\Facades\Mail;

class SendEmailToCompanyControllerNotification
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
    public function handle(CompanyRegistered $event): void
    {
        $users = User::whereHas('permissions', function ($query) {
            $query->where('name', 'companies_control');
        })
            ->whereNotNull('email_verified_at')
            ->get();
        $data = [
            'title' => 'A newly registered company',
            'content' => 'A new company has been registered on the website. You can check its information from the company control section. You can also access it from the link below.',
            'button_url' => route('admin.companies.index'),
            'button_text' => 'Company Control'
        ];
        foreach ($users as $user) {
            Mail::send('vendor.emails.style1', $data, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('A newly registered company');
            });
        }
    }
}
