<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\ProductVendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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

    public function show(Voucher $coupon)
    {
    }

    public function edit(Voucher $coupon)
    {
    }

    public function update(Request $request, Voucher $coupon)
    {
    }

    public function destroy(Voucher $coupon)
    {
    }

    public function applyCoupon(Voucher $coupon)
    {
        $basket = auth()->user()->basket;

        $discountPercent = $coupon->discount_percent;
        $discountAmount = $coupon->discount_price;

        $productsPriceArray = $basket->getTotalValueOfBasket();

        $sumOfProductsPrices = array_sum($productsPriceArray);

        $min_basket_limit = $coupon->min_basket_limit;
        if (!$sumOfProductsPrices >= $min_basket_limit){
            return Response::json(
                data: [
                    'message' => 'the sum of products must be greater than'. $min_basket_limit,
                ]
            );
        }

        if (!is_null($discountPercent)) {
            $discountedPrice = (100 - $discountPercent) * $sumOfProductsPrices * 100;

        $max_discount = $coupon->max_discount;

        if($discountedPrice > $max_discount){
            $discountedPrice = $sumOfProductsPrices - $max_discount;
        }

            return $this->applyDiscount($basket,$coupon, $discountedPrice);

        }
        elseif(!is_null($discountAmount)) {
            $discountedPrice = $sumOfProductsPrices - $discountAmount;
            $discountedPrice = max($discountedPrice, 0);

            return $this->applyDiscount($basket,$coupon, $discountedPrice);
        }
    }


    /**
     * @param $basket
     * @param $discountedPrice
     * @return JsonResponse
     */
    public function applyDiscount(Basket $basket, Voucher $coupon , int $discountedPrice): JsonResponse
    {
        $now = Carbon::now();
        if (!$coupon->used && $now->lte($coupon->expire_date))
        {
        $basket->update([
            'total' => $discountedPrice
        ]);
        $coupon->update([
            'used' => true
        ]);

        return Response::json(
            data: $basket
        );
    }
        return Response::json(
            data: [
                'message' => 'this coupon is already used',
            ]
        );
    }

}
