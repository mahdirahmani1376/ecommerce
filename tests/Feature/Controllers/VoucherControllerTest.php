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
    public function can_coupon_be_applied_on_a_basket_with_a_price_more_than_min_and_less_than_max()
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


        $basketTotal = $basket->getTotalValueOfBasket();
        $basket->total = $basketTotal;

        $voucher = Voucher::factory()->asCoupon()->create([
            'min_basket_limit' => 20,
            'max_discount' => 20000,
            'discount_percent' => $discountPercent = 20,
        ]);

        $discountAmount = $basketTotal * $discountPercent / 100;

        $this->superAdmin->voucher()->attach($voucher);

        $responseCouponApply = $this->postJson(route('apply-voucher', $voucher));

        $this->assertDatabaseHas('baskets', [
            'user_id' => $this->superAdmin->user_id,
            'total' => $basketTotal,
            'discount_amount' => $discountAmount,
            'discounted_price' => $basketTotal - $discountAmount,
        ]);


        $variationVendorPrice = $variationVendor->price;
        $discountAmountVariationVendor = $variationVendorPrice * $discountPercent / 100;
        $this->assertDatabaseHas('baskets_variations',[
            'basket_id' => $basket->basket_id,
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
            'price' => $variationVendorPrice,
            'discount_amount' => $discountAmountVariationVendor,
            'discounted_price' => $variationVendorPrice - $discountAmountVariationVendor,
        ]);

    }

    /** @test */
    public function can_coupon_be_applied_on_a_basket_with_a_price_more_than_min_and_more_than_max()
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
        BasketVariationVendor::factory()->forBasket($basket)->forVariationVendor($variationVendor2)->count(4)->create();


        $basketTotal = $basket->getTotalValueOfBasket();
        $basket->total = $basketTotal;

        $voucher = Voucher::factory()->asCoupon()->create([
            'min_basket_limit' => 20,
            'max_discount' => $max_discount = 4000,
            'discount_percent' => 20,
        ]);

        $this->superAdmin->voucher()->attach($voucher);

        $responseCouponApply = $this->postJson(route('apply-voucher', $voucher));

        $discountPercent = $max_discount * 100 / $basketTotal;
        $discountAmount = $basketTotal * $discountPercent / 100;

        $this->assertDatabaseHas('baskets', [
            'user_id' => $this->superAdmin->user_id,
            'total' => $basketTotal,
            'discount_amount' => $discountAmount,
            'discounted_price' => $basketTotal - $discountAmount,
        ]);


        $variationVendorPrice = $variationVendor->price;
        $discountAmountVariationVendor = $variationVendorPrice * $discountPercent / 100;
        $this->assertDatabaseHas('baskets_variations',[
            'basket_id' => $basket->basket_id,
            'variation_vendor_id' => $variationVendor->variation_vendor_id,
            'price' => $variationVendorPrice,
            'discount_amount' => $discountAmountVariationVendor,
            'discounted_price' => $variationVendorPrice - $discountAmountVariationVendor,
        ]);

    }
}
