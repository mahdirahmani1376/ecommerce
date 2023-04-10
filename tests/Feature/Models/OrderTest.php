<?php

namespace Tests\Feature\Models;

use App\Enums\StockEnum;
use App\Jobs\LowStockEventJob;
use App\Models\Basket;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use App\States\OrderStates\Paid;
use Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\BaseTestCase;

class OrderTest extends BaseTestCase
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
    public function stock_is_created_and_low_stock_email_is_sent()
    {
        Bus::fake([
            LowStockEventJob::class,
        ]);
        $user = auth()->user();
        $vendor = $this->vendor;
        $stock = StockEnum::LowStockEnum->value;
        $product = $this->product;
        $product2 = Product::factory()->create();
        $vendor2 = Vendor::factory()->create();
        $productVendor2 = ProductVendor::factory()->create([
            'product_id' => $product2->product_id,
            'vendor_id' => $vendor2->vendor_id,
            'stock' => 10,
        ]);

        $productVendorUpdateData = ProductVendor::factory(2)->create([
            'vendor_id' => $vendor,
            'stock' => $stock,
        ]);

        $data = [
            'products' => [
                [
                    'product_id' => $product->product_id,
                    'vendor_id' => $vendor->vendor_id,
                ],
                [
                    'product_id' => $product2->product_id,
                    'vendor_id' => $vendor2->vendor_id,
                ],
            ],
        ];

        $response = $this->postJson(route('orders.store', $data));
        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.user.id', $this->superAdmin->id)
            ->count('data.products', 2)
            ->etc()
        );

        $this->assertDatabaseCount('orders_products',3);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('products_vendors',
            [
                'product_id' => $product->product_id,
                'vendor_id' => $vendor->vendor_id,
                'stock' => --$stock,
            ]
        );
        $this->assertDatabaseHas('products_vendors',
            [
                'product_id' => $product2->product_id,
                'vendor_id' => $vendor2->vendor_id,
            ]
        );

        Bus::assertDispatched(LowStockEventJob::class);
    }

    /** @test */
    public function can_an_order_be_updated()
    {
        $user = auth()->user();
        $vendor = $this->vendor;
        $stock = StockEnum::LowStockEnum->value;
        $product = $this->product;
        $product2 = Product::factory()->create();
        $vendor2 = Vendor::factory()->create();
        $productVendor2 = ProductVendor::factory()->create([
            'product_id' => $product2->product_id,
            'vendor_id' => $vendor2->vendor_id,
            'stock' => 10,
        ]);
        $data = [
            'products' => [
                [
                    'product_id' => $product->product_id,
                    'vendor_id' => $vendor->vendor_id,
                ],
                [
                    'product_id' => $product2->product_id,
                    'vendor_id' => $vendor2->vendor_id,
                ],
            ],
        ];

        $order = $this->order;

        $response = $this->putJson(route('order.update', $order), $data);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('order_id', $order->order_id)
            ->where('user.id', $this->superAdmin->id)
            ->has('products')
            ->etc()
        );

        $this->assertDatabaseHas('orders_products', [
            'order_id' => $order->order_id,
            'product_id' => $product->product_id,
        ]);
    }

    /** @test */
    public function can_an_order_be_deleted()
    {
        $order = Order::factory()->create([
            'user_id' => $this->superAdmin->id,
        ]);

        $orderProducts = OrderProduct::create([
            'vendor_id' => $this->vendor->vendor_id,
            'product_id' => $this->product->product_id,
            'order_id' => $order->order_id,
        ]);

        $this->productVendor->decrement('stock');

        $this->assertDatabaseHas('products_vendors',[
            'product_id' => $this->product->product_id,
            'stock' => $this->stock -1
        ]);

        $response = $this->delete(route('order.destroy',$order));

        $this->assertDatabaseHas('products_vendors',[
            'product_id' => $this->product->product_id,
            'stock' => $this->stock
        ]);

    }

    /** @test */
    public function can_coupon_be_applied_on_an_order()
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

        $coupon = Coupon::factory()->create([
            'discount_percent' => 20,
            'max_discount' => 300,
            'min_basket_limit' => 100,
        ]);

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

        $responseCouponApply = $this->postJson(route('apply-coupon',$coupon));

    }
}
