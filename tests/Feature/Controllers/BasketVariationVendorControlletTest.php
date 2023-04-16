<?php

namespace Tests\Feature\Controllers;

use App\Enums\StockEnum;
use App\Jobs\LowStockEventJob;
use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\Feature\BaseTestCase;

class BasketVariationVendorControlletTest extends BaseTestCase
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
    public function stock_is_created_and_low_stock_email_is_sent()
    {
        Bus::fake([
            LowStockEventJob::class,
        ]);

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

        Bus::assertDispatched(LowStockEventJob::class);
    }
}
