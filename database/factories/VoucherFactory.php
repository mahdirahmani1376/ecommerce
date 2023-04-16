<?php

namespace Database\Factories;

use App\Enums\VoucherEnum;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'expire_date' => Carbon::now()->addDays(random_int(1, 30)),
        ];
    }

    public function asGifcard()
    {
        return $this->state(function (array $attributes) {
            return [
                'voucher_enum' => VoucherEnum::giftCard->value,
                'discount_price' => random_int(1, 10000),
            ];
        });
    }

    public function asCoupon()
    {
        return $this->state(function (array $attributes) {
            return [
                'voucher_enum' => VoucherEnum::coupon->value,
                'discount_percent' => random_int(0, 100),
                'max_discount' => random_int(0, 10000),
                'min_basket_limit' => random_int(1, 200),
            ];
        });
    }
}
