<?php

namespace App\Events;

use App\Models\ProductVendor;
use Illuminate\Foundation\Events\Dispatchable;

class LowStockEvent
{
    use Dispatchable;

    public ProductVendor $productVendor;

    public function __construct(
        ProductVendor $productVendor
    ) {
        $this->productVendor = $productVendor;
    }
}
