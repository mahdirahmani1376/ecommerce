<?php

namespace App\Jobs;

use App\Models\VariationVendor;
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

    public function __construct(
        protected VariationVendor $variationVendor
    ) {
    }

    public function handle()
    {
        $variationVendor = $this->variationVendor;
        Notification::send($variationVendor->vendor, new LowStockNotification($variationVendor));
    }
}
