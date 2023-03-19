<?php

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use App\States\OrderStates\Paid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class OrderTest extends BaseTestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_an_order_be_created()
    {
        $user = auth()->user();

        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create();
        $product->vendors()->attach($vendor);

        $data = [
            'product' => $product->toArray(),
            'vendor' => $vendor->toArray(),
        ];

        $response = $this->postJson(route('orders.store'),$data);

        $response->assertStatus(201);

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.products.0.name',$product->name)
            ->where('data.user.name',$user->name)
            ->etc()
        );
        $this->assertDatabaseHas('orders',[
            'product_id' => $product->product_id,
            'user_id' => $user->id,
        ]);

    }

    /** @test */
    public function can_an_order_statues_change()
    {
        $user = auth()->user();
        $product = Product::factory()->create();

        $order = Order::factory()->forUser($user)->forProduct($product)->create();

        $order->state->transitionTo(Paid::class);

        $this->assertDatabaseHas('orders',[
            'order_id' => $order->order_id,
            'state' => Paid::$name,
        ]);
    }

}
