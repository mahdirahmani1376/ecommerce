<?php

namespace App\Observers;

use App\Enums\StockEnum;
use App\Jobs\LowStockEventJob;
use App\Models\BasketVariationVendor;

class BasketVariationVendorObserver
{
    public function created(BasketVariationVendor $basketVariationVendor)
    {
        $this->decrementStock($basketVariationVendor);
        $this->checkStock($basketVariationVendor);
    }

    public function updated(BasketVariationVendor $basketVariationVendor)
    {
        $basketVariationVendor->variationVendor()->decrement('stock');
    }

    public function deleted(BasketVariationVendor $basketVariationVendor)
    {
        $this->incrementStock($basketVariationVendor);
    }

    public function restored(BasketVariationVendor $basketVariationVendor)
    {
        $this->decrementStock($basketVariationVendor);
    }

    public function forceDeleted(BasketVariationVendor $basketVariationVendor)
    {
        $this->incrementStock($basketVariationVendor);
    }

    public function checkStock(BasketVariationVendor $basketVariationVendor)
    {
        $variationVendor = $basketVariationVendor->variationVendor;
        if ($variationVendor?->stock <= StockEnum::LowStockEnum->value) {
            LowStockEventJob::dispatch($variationVendor);
        }
    }

    public function incrementStock(BasketVariationVendor $basketVariationVendor): void
    {
        $basketVariationVendor->variationVendor()->increment('stock');
    }

    public function decrementStock(BasketVariationVendor $basketVariationVendor): void
    {
        $basketVariationVendor->variationVendor()->decrement('stock');
    }
}
