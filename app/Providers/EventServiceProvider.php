<?php

namespace App\Providers;

use App\Events\LowStockEvent;
use App\Listeners\LowStockListener;
use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\Order;
use App\Models\ProductVendor;
use App\Observers\BasketObserver;
use App\Observers\BasketVariationVendorObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductVendorObserver;
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
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LowStockEvent::class => [
            LowStockListener::class,
        ],
    ];

    protected $observers = [
        //        ProductVendor::class => [
        //            ProductVendorObserver::class,
        //        ],
        Order::class => [
            OrderObserver::class,
        ],
        Basket::class => [
            BasketObserver::class,
        ],
        BasketVariationVendor::class => [
            BasketVariationVendorObserver::class,
        ],
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
