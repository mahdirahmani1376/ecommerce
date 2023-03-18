<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_id' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }

    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    public function forProduct(Product $product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product->product_id,
            ];
        });
    }


}
