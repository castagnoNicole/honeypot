<?php

namespace App\Providers;

use App\Events\CreatedUser;
use App\Events\HoneypotUserRetrieved;
use App\Events\XSSDetected;
use App\Listeners\LogCreatedUser;
use App\Listeners\LogHoneypotUserRetrieved;
use App\Listeners\LogXSSDetected;
use App\Models\User;
use App\Observers\HoneypotUserObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
/*    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];*/

    protected $listen = [
        XSSDetected::class => [
            LogXSSDetected::class,
        ],
        HoneypotUserRetrieved::class => [
            LogHoneypotUserRetrieved::class,
        ],
        CreatedUser::class => [
            LogCreatedUser::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        User::observe(HoneypotUserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
