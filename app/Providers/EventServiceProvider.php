<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\DevicePrice;
use App\Models\MainWarehouseDevice;
use App\Models\User;
use App\Models\WarehouseDeviceRequest;
use App\Observers\CampaignObserver;
use App\Observers\MainWarehouseDeviceObserver;
use App\Observers\UserObserver;
use App\Observers\WarehouseDeviceRequestObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Campaign::observe(CampaignObserver::class);
        MainWarehouseDevice::observe(MainWarehouseDeviceObserver::class);
        User::observe(UserObserver::class);
        WarehouseDeviceRequest::observe(WarehouseDeviceRequestObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
