<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'rating' => random_int(1,5),
            'description' => $this->faker->text(),
            'weight' => random_int(1,500),
        ];
    }
}
