<?php

namespace App\Providers;

use App\Events\CompanyActivedEvent;
use App\Events\CompanyInformEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CompanyRegistered;
use App\Listeners\SendEmailToCompanyControllerNotification;
use App\Listeners\SendActivedEmailToCompanyNotification;
use App\Listeners\SendEmailToCompanyToInformNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CompanyRegistered::class => [
            SendEmailToCompanyControllerNotification::class,
        ],
        CompanyActivedEvent::class => [
            SendActivedEmailToCompanyNotification::class,
        ],
        CompanyInformEvent::class => [
            SendEmailToCompanyToInformNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
