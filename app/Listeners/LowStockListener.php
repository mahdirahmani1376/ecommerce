<?php

namespace App\Listeners;

use App\Events\LowStockEvent;
use App\Jobs\LowStockEventJob;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class LowStockListener
{
    public function __construct()
    {
    }

    public function handle(LowStockEvent $event)
    {
        $productVendor = $event->productVendor;
        $vendor = $productVendor->vendor;
        Notification::send($vendor,new LowStockNotification($productVendor));
    }
}
