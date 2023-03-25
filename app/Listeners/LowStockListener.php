<?php

namespace App\Listeners;

use App\Events\LowStockEvent;
use App\Jobs\LowStockEventJob;
use App\Notifications\LowStockNotification;

class LowStockListener
{
    public function __construct()
    {
    }

    public function handle(LowStockEvent $event)
    {
        LowStockEventJob::dispatch($event);
    }
}
