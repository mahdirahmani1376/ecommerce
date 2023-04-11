<?php

namespace Tests\Feature\Models;

use App\Enums\StockEnum;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class BasketTest extends BaseTestCase
{
    use RefreshDatabase;

    private Order $order;

    private ProductVendor $productVendor;

    private Product $product;

    private Vendor $vendor;

    private int $stock;

    protected function setUp(): void
    {
        parent::SetUp();

        $product = Product::factory()->create();
        $vendor = Vendor::factory()->create();
        $stock = StockEnum::LowStockEnum->value;
        $productVendor = ProductVendor::factory()->create([
            'vendor_id' => $vendor->vendor_id,
            'product_id' => $product->product_id,
            'stock' => $stock,
        ]);
        $order = Order::factory()->create([
            'user_id' => $this->superAdmin->id,
        ]);

        $orderProduct = OrderProduct::create([
            'product_id' => $product->product_id,
            'vendor_id' => $vendor->vendor_id,
            'order_id' => $order->order_id,
        ]);
        $this->order = $order;
        $this->productVendor = $productVendor;
        $this->product = $product;
        $this->vendor = $vendor;
        $this->stock = $stock;
    }

    /** @test */
    public function can_coupon_be_applied_on_a_basket()
    {
        $user = auth()->user();
        $vendor = $this->vendor;
        $stock = StockEnum::LowStockEnum->value;
        $product = $this->product;
        $product2 = Product::factory()->create();
        $vendor2 = Vendor::factory()->create();
        $productVendor = ProductVendor::factory()->create([
            'price' => 2000,
            'product_id' => $product->product_id,
            'vendor_id' => $vendor->vendor_id,
        ]);

        $coupon = Voucher::factory()->create([
            'discount_percent' => 20,
            'max_discount' => 300,
            'min_basket_limit' => 100,
        ]);

        $this->superAdmin->coupon()->attach($coupon);

        $data = [
            'products' => [
                [
                    'product_id' => $product->product_id,
                    'vendor_id' => $vendor->vendor_id,
                ],
            ],
        ];

        $responseAddToBasket = $this->postJson(route('add-to-basket'),$data);
        $responseAddToBasket->assertStatus(200);

        $basket = $user->basket;
        $productsPriceArray = $basket->getTotalValueOfBasket();
        $sumOfProductsPrices = array_sum($productsPriceArray);

        $responseCouponApply = $this->postJson(route('apply-coupon',$coupon));


        $this->assertDatabaseHas('baskets',[
            'user_id' => $this->superAdmin->id,
            'total' =>   $sumOfProductsPrices - 300
        ]);

    }
}
