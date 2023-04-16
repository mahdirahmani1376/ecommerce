<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\VariationVendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class BasketVariationVendorFactory extends Factory
{
    protected $model = BasketVariationVendor::class;

    public function definition(): array
    {
        return [

        ];
    }

    public function forBasket(Basket $basket): Factory
    {
        if (! isset($basket)) {
            $basket = Basket::factory()->create();
        }

        return $this->state(function (array $attributes) use ($basket) {
            return [
                'basket_id' => $basket->basket_id,
            ];
        });
    }

    public function forVariationVendor(VariationVendor $variationVendor): Factory
    {
        if (! isset($variationVendor)) {
            $variationVendor = VariationVendor::factory()->create();
        }

        return $this->state(function (array $attributes) use ($variationVendor) {
            return [
                'variation_vendor_id' => $variationVendor->variation_vendor_id,
                'price' => $variationVendor->price,
            ];
        });
    }
}
