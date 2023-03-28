<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
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
        return Response::json(OrderResource::make(Order::with('productsVendors','user')->paginate()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {

        $validated = $request->validated();
        $productsIds = collect($validated['product_vendors'])->pluck('id')->all();

        $productVendors = ProductVendor::findMany($productsIds);
        $order = Order::create([
            'user_id' => auth()->id(),
        ]);

        $order->productsVendors()->sync($productVendors);

        $productVendors->each(function (ProductVendor $productVendor){
            $productVendor->update([
                'stock' => --$productVendor->stock
            ]);
        });

        return OrderResource::make($order->load('user','productsVendors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Response::json(OrderResource::make($order->with('user','productsVendors')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        $productsVendorIds = collect($validated['product_vendors'])->pluck('id');

        $order->productsVendors()->sync($productsVendorIds);

        return Response::json(OrderResource::make($order->load('user','productsVendors')));
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
