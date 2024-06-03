<?php

namespace App\Listeners;

use App\Events\CompanyRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User\User;
use Illuminate\Support\Facades\Mail;

class SendCompanyControlNotification
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
        })->get();
        $data = [
            'title' => 'A newly registered company',
            'content' => 'A new company has been registered on the website. You can check its information from the company control section. You can also access it from the link below.',
            'button_url' => route('admin.companies.index'),
            'button_text' => 'Company Control'
        ];
        foreach ($users as $user) {
            Mail::send('vendor.emails.new_company_notification', $data, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('A newly registered company');
            });
        }
    }
}
