<?php

namespace App\Jobs;

use App\Models\ProductVendor;
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

    private ProductVendor $productVendor;

    public function __construct(ProductVendor $productVendor)
    {
        $this->productVendor = $productVendor;
    }

    public function handle()
    {
        $productVendor = $this->productVendor;
        $vendor = $productVendor->vendor;
        Notification::send($vendor, new LowStockNotification($productVendor));
    }
}
