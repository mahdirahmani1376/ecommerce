<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Response::json(OrderResource::make(Order::with('products','user')->paginate()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $vendor = Vendor::find($validated['vendor']['vendor_id']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'vendor_id' => $validated['vendor']['vendor_id'],
        ]);

        collect($validated['products'])->each(function ($data) use ($order, $vendor, $validated) {
            $product = Product::find($data['product_id']);

            $productVendor = ProductVendor::where([
                'vendor_id' => $vendor->vendor_id,
                'product_id' => $product->product_id,
            ])->first();

            $stock = $productVendor->stock;

            if ($stock <= 0){
                return Response::json(['message' => 'product is out of stock']);
            }

            $productVendor->update([
                'stock' => --$productVendor->stock,
            ]);

            $order->products()->sync($product->product_id);
        });

        return OrderResource::make($order->load('user','products','vendor'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Response::json(OrderResource::make($order->with('user','products')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        $order->update([
            'product_id' => $validated['product']['product_id'],
            'vendor_id' => $validated['vendor']['vendor_id'],
        ]);

        return Response::json(OrderResource::make($order->load('user','products')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return Response::json();
    }
}
