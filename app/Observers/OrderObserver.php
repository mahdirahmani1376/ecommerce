<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\ProductVendor;

class OrderObserver
{
    public function created(Order $order)
    {
    }

    public function updated(Order $order)
    {
    }

    public function deleted(Order $order)
    {
    }

    public function restored(Order $order)
    {
    }

    public function forceDeleted(Order $order)
    {
    }
}
