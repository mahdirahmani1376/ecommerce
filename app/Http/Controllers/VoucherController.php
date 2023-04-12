<?php

namespace App\Http\Controllers;

use App\Enums\VoucherEnum;
use App\Models\Basket;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VoucherController extends Controller
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

    public function show(Voucher $voucher)
    {
    }

    public function edit(Voucher $voucher)
    {
    }

    public function update(Request $request, Voucher $voucher)
    {
    }

    public function destroy(Voucher $voucher)
    {
    }

    public function applyVoucher(Voucher $voucher)
    {
        $basket = auth()->user()->basket;
        if ($voucher->voucher_enum === VoucherEnum::coupon->value) {
            return $this->applyCoupon($voucher, $basket);
        } elseif ($voucher->voucher_enum === VoucherEnum::giftCard->value) {
            return $this->applyGiftCard($voucher, $basket);
        }
    }

    public function applyCoupon(Voucher $voucher, Basket $basket)
    {
        $now = Carbon::now();
        if (! $voucher->used && $now->lte($voucher->expire_date)) {
            $discountPercent = $voucher->discount_percent;
            $sumOfProductsPrices = $basket->getTotalValueOfBasket();
            $min_basket_limit = $voucher->min_basket_limit;
            $max_discount = $voucher->max_discount;

            if (! $sumOfProductsPrices >= $min_basket_limit) {
                return Response::json(
                    data: [
                        'message' => 'the sum of products must be greater than'.$min_basket_limit,
                    ]
                );
            }

            $discountAmount = (100 - $discountPercent) * $sumOfProductsPrices / 100;
            if ($discountAmount > $max_discount) {
                $discountedPrice = $sumOfProductsPrices - $max_discount;
            } else {
                $discountedPrice = $sumOfProductsPrices - $discountAmount;
                }

            $voucher->update([
                'used' => true,
            ]);

            return $this->applyDiscount($basket, $discountedPrice, $discountAmount);

        } else {
            return Response::json(
                data: [
                    'message' => 'this voucher is already used or it is expired',
                ]);
        }
    }

    public function applyGiftCard(Voucher $voucher, Basket $basket)
    {
        $now = Carbon::now();
        if (! $voucher->used && $now->lte($voucher->expire_date)) {
            $discountAmount = $voucher->discount_price;
            $sumOfProductsPrices = $basket->getTotalValueOfBasket();
            $discountedPrice = $sumOfProductsPrices - $discountAmount;
            $discountedPrice = max($discountedPrice, 0);
            $remainingDiscount = max($discountAmount - $discountedPrice, 0);

            $voucher->update([
                'discount_price' => $remainingDiscount,
                'used' => ! ($remainingDiscount > 0),
            ]);

            return $this->applyDiscount($basket, $discountedPrice, $discountAmount);
        } else {
            return Response::json(
                data: [
                    'message' => 'this voucher is already used or it is expired',
                ]);
        }
    }

    public function applyDiscount(Basket $basket, int $discountedPrice, int $discountAmount): JsonResponse
    {
        $basket->update([
            'discounted_price' => $discountedPrice,
            'discount_amount' => $discountAmount,
        ]);

        return Response::json(
            data: $basket
        );
    }
}
