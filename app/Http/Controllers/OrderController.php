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
        $productsIds = collect($validated['products'])->pluck('product_id')->all();
        $vendorIds = collect($validated['products'])->pluck('vendor_id')->all();

        $order = Order::create([
            'user_id' => auth()->id(),
        ]);

        $productVendors = ProductVendor::where(function (Builder $builder) use ($productsIds,$vendorIds){
           $builder
               ->whereIn('product_id',$productsIds)
               ->whereIn('vendor_id',$vendorIds);
        })->get();

        $order->products()->sync($productsIds);

        $productVendors->each(function (ProductVendor $productVendor){
            $productVendor->update([
                'stock' => --$productVendor->stock
            ]);
        });

        return OrderResource::make($order->load('user','products'));
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
        $productsIds = collect($validated['products'])->pluck('product_id')->all();
        $vendorIds = collect($validated['products'])->pluck('vendor_id')->all();

        $productVendors = ProductVendor::where(function (Builder $builder) use ($productsIds,$vendorIds){
            $builder
                ->whereIn('product_id',$productsIds)
                ->whereIn('vendor_id',$vendorIds);
        })->get();

        $order->products()->sync($productsIds);

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
