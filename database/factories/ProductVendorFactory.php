<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVendorFactory extends Factory
{
    protected $model = ProductVendor::class;

    public function definition(): array
    {
        return [

            'product_id' => Product::factory(),
            'vendor_id' => Vendor::factory(),
            'price' => random_int(100,1000),
            'stock' => random_int(0,10),
        ];
    }
}
