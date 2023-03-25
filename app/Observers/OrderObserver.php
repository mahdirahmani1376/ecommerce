<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\ProductVendor;

class OrderObserver
{
    public function created(Order $order)
    {
//        $vendor = $order->vendor;
//        $order->products->each(function ($product) use ($order,$vendor){
//            $productVendor = ProductVendor::where([
//                'vendor_id' => $vendor->vendor_id,
//                'product_id' => $product->product_id,
//            ])->first();
//            $productVendor->update([
//                'stock' => --$productVendor->stock
//            ]);
//        });
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
