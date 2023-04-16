<?php

namespace App\Observers;

use App\Models\Basket;
use App\Models\BasketVariationVendor;

class BasketObserver
{
    public function created(Basket $basket)
    {
    }

    public function updated(Basket $basket)
    {
    }

    public function deleted(Basket $basket)
    {
        $basket->basketVariationVendor()->each(function (BasketVariationVendor $basketVariationVendor) {
            $basketVariationVendor->delete();
        });
    }

    public function restored(Basket $basket)
    {
    }

    public function forceDeleted(Basket $basket)
    {
    }
}
