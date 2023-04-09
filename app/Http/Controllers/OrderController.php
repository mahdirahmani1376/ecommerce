<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductVendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Response::json(OrderResource::make(Order::with('productsVendors', 'user')->paginate()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create([
            'user_id' => auth()->id(),
        ]);

        foreach ($validated['products'] as $key => $value) {
            $productVendor = ProductVendor::where(function (Builder $builder) use ($value) {
                $builder
                    ->where('product_id', $value['product_id'])
                    ->where('vendor_id', $value['vendor_id']);
            });

            if ($productVendor->exists()){
                $productVendor->decrement('stock');
                $productVendor = $productVendor->first();
                OrderProduct::create([
                    'product_id' => $productVendor->product_id,
                    'vendor_id' => $productVendor->vendor_id,
                    'order_id' => $order->order_id,
                ]);
            }

        }

        return OrderResource::make($order->load('user', 'products'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Response::json(OrderResource::make($order->with('user', 'products')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        foreach ($validated['products'] as $key => $value) {
            $productVendor = ProductVendor::where(function (Builder $builder) use ($value) {
                $builder
                    ->where('product_id', $value['product_id'])
                    ->where('vendor_id', $value['vendor_id']);
            });

            if ($productVendor->exists()){
                $productVendor->decrement('stock');
                $productVendor = $productVendor->first();
                OrderProduct::create([
                    'product_id' => $productVendor->product_id,
                    'vendor_id' => $productVendor->vendor_id,
                    'order_id' => $order->order_id,
                ]);
            }

        }

        return Response::json(OrderResource::make($order->load('user', 'products')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->products()->each(function (OrderProduct $orderProduct){
           ProductVendor::where([
               'product_id' => $orderProduct->product_id,
               'vendor_id' => $orderProduct->vendor_id,
           ])
               ->increment('stock');
        });

        $order->delete();

        return Response::json([
            'message' => 'Order deleted successfully'
        ]);
    }

    private function applyCoupon(ProductVendor $productVendor,Coupon $coupon)
    {
//        $productVendor->price
    }

}
