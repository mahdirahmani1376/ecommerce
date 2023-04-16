<?php

namespace Tests\Feature\Models;

use App\Enums\StockEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class BasketTest extends BaseTestCase
{
    use RefreshDatabase;

    private Order $order;

    private Product $product;

    private Vendor $vendor;

    private int $stock;

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
        $this->variationVendor = $variationVendor;
    }

    /** @test */
    public function can_a_product_be_added_to_a_basket()
    {
        $user = $this->superAdmin;
        $vendor = $this->vendor;
        $product = $this->product;
        $stock = $this->stock;
        $variationVendor = $this->variationVendor;

        $response = $this->postJson(route('basket.add-to-basket', $variationVendor));

        $this->assertDatabaseHas('baskets', [
            'user_id' => $user->basket->user_id,
        ]);

        $this->assertDatabaseHas('basket_variation_vendors', [
            'basket_id' => $user->basket->basket_id,
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
        ]);
    }
}
