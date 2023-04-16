<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\VariationVendor;
use Illuminate\Support\Facades\Response;

class BasketController extends Controller
{
    public function addToBasket(VariationVendor $variationVendor)
    {
        $user = auth()->user();

        $basket = Basket::updateOrCreate([
            'user_id' => $user->user_id,
        ]);

        $basket->basketVariationVendor()->create([
            'price' => $variationVendor->price,
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
        ]);

        $variationVendor->stock--;

        return Response::json($basket->load('basketVariationVendor'));
    }

    public function removeFromBasket(BasketVariationVendor $basketVariation)
    {
        $basket = auth()->user()->basket;

        $basket->basketVariationVendor()->delete($basketVariation);

        $basketVariation->variationVendor()->increment('stock');

        return Response::json($basket->load('variationVendor'));
    }
}
