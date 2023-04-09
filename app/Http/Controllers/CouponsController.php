<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Coupon $coupon)
    {
    }

    public function edit(Coupon $coupon)
    {
    }

    public function update(Request $request, Coupon $coupon)
    {
    }

    public function destroy(Coupon $coupon)
    {
    }

    public function applyCoupon(Basket $basket)
    {

        $totalValueOfBasketPrice = $basket->products()->pluck('price')->sum();
    }
}
