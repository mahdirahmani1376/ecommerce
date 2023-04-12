<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\VariationVendor;
use Illuminate\Support\Facades\Response;

class BasketController extends Controller
{
    public function addToBasket(VariationVendor $variationVendor)
    {
        $user = auth()->user();

        $basket = Basket::updateOrCreate([
            'user_id' => $user->id,
        ]);

        $basket->variationVendor()->attach($variationVendor);
        $variationVendor->stock--;

        return Response::json($basket->load('variationVendor'));
    }

    public function removeFromBasket(VariationVendor $variationVendor)
    {
        $basket = auth()->user()->basket;

        $basket->variationVendor()->dettach($variationVendor);
        $variationVendor->stock++;

        return Response::json($basket->load('variationVendor'));

    }
}
