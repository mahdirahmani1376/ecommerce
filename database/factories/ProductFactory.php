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
            'rating' => $this->faker->randomNumber(),
            'description' => $this->faker->text(),
            'in_stock' => $this->faker->boolean(),
            'weight' => $this->faker->randomNumber(),
        ];
    }
}
