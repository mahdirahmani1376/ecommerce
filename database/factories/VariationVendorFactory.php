<?php

namespace Database\Factories;

use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariationVendorFactory extends Factory
{
    protected $model = VariationVendor::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'price' => random_int(0, 10000),
            'discounted_price' => random_int(0, 10000),
            'stock' => random_int(0, 10),
        ];
    }

    public function forVariation($variation): Factory
    {
        if (! isset($variation)) {
            $variation = Variation::factory()->create();
        }

        return $this->state(function (array $attributes) use ($variation) {
            return [
                'product_id' => $variation->product_id,
                'variation_id' => $variation->variation_id,
            ];
        });
    }
}
