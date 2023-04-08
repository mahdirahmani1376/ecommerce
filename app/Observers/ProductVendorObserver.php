<?php

namespace App\Observers;

use App\Enums\StockEnum;
use App\Jobs\LowStockEventJob;
use App\Models\ProductVendor;

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
        if ($productVendor->stock <= StockEnum::LowStockEnum->value) {
            LowStockEventJob::dispatch($productVendor);
        }
    }
}
