<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToBasketRequest;
use App\Http\Resources\OrderResource;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductVendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BasketController extends Controller
{
    public function addToBasket(AddToBasketRequest $addToBasketRequest)
    {
        $user = auth()->user();

        $basket = Basket::updateOrCreate([
            'user_id' => $user->id,
        ]);

        $validated = $addToBasketRequest->validated();

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
                BasketProduct::create([
                    'product_id' => $productVendor->product_id,
                    'vendor_id' => $productVendor->vendor_id,
                    'basket_id' => $basket->basket_id,
                ]);
            }

        }

        return Response::json($basket->load('products'));
    }

    public function removeFromBasket(ProductVendor $productVendor)
    {
        $user = auth()->user();
        if ($user->has('basket'))
        {
            $user->basket()->productVendors()->dettach($productVendor);
            return Response::json($user->basket->load(['productVendors' => ['product']]));
        }
        else{
            $basket = $user->basket()->create();
            $basket->dettach($productVendor);
            return Response::json($user->basket->load(['productVendors' => ['product']]));
        }
    }
}
