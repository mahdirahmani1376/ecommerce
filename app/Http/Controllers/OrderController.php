<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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

        $order = Order::create([
            'product_id' => $validated['product']['product_id'],
            'user_id' => auth()->id(),
            'vendor_id' => $validated['vendor']['vendor_id'],
        ]);

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
