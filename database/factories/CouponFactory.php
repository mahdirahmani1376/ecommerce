<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'discount_percent' => random_int(0,100),
            'max_discount' => random_int(100,500),
            'min_basket_limit' => random_int(501,1000),
            ''
        ];
    }
}
