<?php

namespace Tests\Feature\Models;

use App\Enums\StockEnum;
use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use App\States\OrderStates\Paid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class OrderTest extends BaseTestCase
{
    use RefreshDatabase;

    private Order $order;

    private Product $product;

    private Vendor $vendor;

    private int $stock;

    private Variation $variation;

    protected function setUp(): void
    {
        parent::SetUp();

        $product = Product::factory()->create();
        $vendor = Vendor::factory()->create();
        $stock = StockEnum::LowStockEnum->value;
        $variation = Variation::factory()->for($product)->create();

        $variationVendor = VariationVendor::factory()->for($variation, 'variation')->create();

        $this->product = $product;
        $this->vendor = $vendor;
        $this->stock = $stock;
        $this->variation = $variation;
        $this->variationVendor = $variationVendor;
    }

    /** @test */
    public function can_an_order_statues_change()
    {
        $order = Order::factory()->create();

        $order->state->transitionTo(Paid::class);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'state' => Paid::$name,
        ]);
    }

    /** @test */
    public function can_order_be_created()
    {
        $user = $this->superAdmin;
        $vendor = $this->vendor;
        $product = $this->product;
        $stock = $this->stock;
        $variation = $this->variation;
        $variationVendors = VariationVendor::factory()->count(5)->for($variation, 'variation')->create();
        $total = $variationVendors->sum('price');
        $basket = Basket::factory()->create([
            'user_id' => $user->user_id,
            'total' => $total,
        ]);

        $variationVendors->each(function (VariationVendor $variationVendor) use ($basket) {
            BasketVariationVendor::factory()->forBasket($basket)->forVariationVendor($variationVendor)
                ->create();
        });

        $response = $this->postJson(route('order.store'), $basket->toArray());

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'basket_id' => $basket->basket_id,
        ]);
    }

    /** @test */
    public function can_an_order_be_deleted()
    {
        $user = $this->superAdmin;
        $vendor = $this->vendor;
        $product = $this->product;
        $stock = $this->stock;
        $variation = $this->variation;
        $variationVendor = VariationVendor::factory()->for($variation, 'variation')->create([
            'stock' => $stock,
        ]);
        $total = $variationVendor->sum('price');
        $basket = Basket::factory()->create([
            'user_id' => $user->user_id,
            'total' => $total,
        ]);

        $basketVariationVendor = BasketVariationVendor::factory()
            ->forBasket($basket)
            ->forVariationVendor($variationVendor)
            ->create();

        $order = Order::factory()->create([
            'user_id' => $this->superAdmin->user_id,
            'basket_id' => $basket->basket_id,
        ]);

        $this->assertDatabaseHas('variations_vendors', [
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
            'stock' => $stock - 1,
        ]);

        $response = $this->delete(route('order.destroy', $order));

        $this->assertDatabaseHas('variations_vendors', [
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
            'stock' => $stock,
        ]);
    }
}
