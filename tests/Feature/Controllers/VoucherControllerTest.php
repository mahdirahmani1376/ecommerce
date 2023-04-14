<?php

namespace Tests\Feature\Controllers;

use App\Enums\StockEnum;
use App\Models\Basket;
use App\Models\BasketVariationVendor;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class VoucherControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::SetUp();

    }

    /** @test */
    public function can_coupon_be_applied_on_a_basket()
    {
        $product = Product::factory()->create();
        $vendor = Vendor::factory()->create();
        $stock = 10;
        $variation = Variation::factory()->for($product)->create();

        $variationVendor = VariationVendor::factory()->create([
            'price' => 2000,
            'vendor_id' => $vendor->vendor_id,
            'stock' => $stock,
            'variation_id' => $variation->variation_id,
        ]);
        $variationVendor2 = VariationVendor::factory()->create([
            'price' => 4000,
            'vendor_id' => $vendor->vendor_id,
            'stock' => $stock,
            'variation_id' => $variation->variation_id,
        ]);
        $basket = Basket::factory()
            ->create([
                'user_id' => $this->superAdmin->user_id,
            ]);

        BasketVariationVendor::factory()->forBasket($basket)->forVariationVendor($variationVendor)->count(4)->create();
        BasketVariationVendor::factory()->forBasket($basket)->forVariationVendor($variationVendor2)->count(2)->create();


        $basket->total = $basket->getTotalValueOfBasket();

        $voucher = Voucher::factory()->asCoupon()->create([
            'min_basket_limit' => 20,
            'max_discount' => 3000,
        ]);
        $this->superAdmin->voucher()->attach($voucher);

        $responseCouponApply = $this->postJson(route('apply-voucher', $voucher));

        $this->assertDatabaseHas('baskets', [
            'user_id' => $this->superAdmin->user_id,
            'discount_amount' => $basket->discount_amount,
        ]);

    }
}
