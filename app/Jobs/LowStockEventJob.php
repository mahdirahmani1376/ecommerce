<?php

namespace App\Jobs;

use App\Events\LowStockEvent;
use App\Notifications\LowStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class LowStockEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private LowStockEvent $event;

    public function __construct(LowStockEvent $event)
    {
        $this->event = $event;
    }

    public function handle()
    {
        $vendor = $this->event->productVendor->vendor;
        Notification::send($vendor,new LowStockNotification($this->event->productVendor));
    }
}
