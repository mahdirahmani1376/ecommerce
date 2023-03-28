<?php

namespace App\Observers;

use App\Enums\StockEnum;
use App\Events\LowStockEvent;
use App\Jobs\LowStockEventJob;
use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use function event;

class ProductVendorObserver
{
    public function created(ProductVendor $productVendor)
    {
        $this->checkStock($productVendor);

    }

    public function updated(ProductVendor $productVendor)
    {
        $this->checkStock($productVendor);
    }

    public function deleted(ProductVendor $productVendor)
    {
//        $this->checkStock($productVendor);
    }

    public function restored(ProductVendor $productVendor)
    {
//        $this->checkStock($productVendor);
    }

    public function forceDeleted(ProductVendor $productVendor)
    {
//        $this->checkStock($productVendor);
    }

    public function checkStock(ProductVendor $productVendor)
    {

        if ($productVendor->stock <= StockEnum::LowStockEnum->value)
        {
            LowStockEventJob::dispatch($productVendor);
        }
    }
}
