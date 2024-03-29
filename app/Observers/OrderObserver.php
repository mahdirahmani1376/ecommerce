<?php

namespace App\Observers;

use App\Models\Order;

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
        $order->basket->delete();
    }

    public function restored(Order $order)
    {
    }

    public function forceDeleted(Order $order)
    {
    }

    public function checkStock(Order $order)
    {
    }
}
