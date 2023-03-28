<?php

namespace Tests\Feature\Models;

use App\Enums\StockEnum;
use App\Events\LowStockEvent;
use App\Jobs\LowStockEventJob;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use App\Notifications\LowStockNotification;
use App\States\OrderStates\Paid;
use Bus;
use DB;
use Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Testing\Fluent\AssertableJson;
use LaravelIdea\Helper\App\Models\_IH_Order_C;
use LaravelIdea\Helper\App\Models\_IH_Product_C;
use LaravelIdea\Helper\App\Models\_IH_ProductVendor_C;
use LaravelIdea\Helper\App\Models\_IH_Vendor_C;
use Notification;
use phpDocumentor\Reflection\Types\Void_;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class OrderTest extends BaseTestCase
{

    use RefreshDatabase;

    private Order $order;
    private ProductVendor $productVendor;
    private Product $product;
    private Vendor $vendor;

    protected function setUp(): Void
    {
        parent::SetUp();

        $product =Product::factory()->create();
        $vendor = Vendor::factory()->create();
        $productVendor = ProductVendor::factory()->create([
            'vendor_id' => $vendor->vendor_id,
            'product_id' => $product->product_id,
        ]);
        $order = Order::factory()->create([
            'user_id' => $this->superAdmin->id,
        ]);

        $order->productsVendors()->sync($productVendor);
        $this->order = $order;
        $this->productVendor = $productVendor;
        $this->product = $product;
        $this->vendor = $vendor;
    }

    /** @test */
    public function can_an_order_statues_change()
    {
        $order = Order::factory()->create();

        $order->state->transitionTo(Paid::class);

        $this->assertDatabaseHas('orders',[
            'order_id' => $order->order_id,
            'state' => Paid::$name,
        ]);
    }

    /** @test */
    public function stock_is_created_and_low_stock_email_is_sent()
    {
        Bus::fake([
            LowStockEventJob::class
        ]);
        $productVendorsCount = 2;
        $user = auth()->user();
        $vendor = $this->vendor;
        $stock = StockEnum::LowStockEnum->value;
        $productVendorUpdateData = ProductVendor::factory($productVendorsCount)->create([
            'vendor_id' => $vendor,
            'stock' => $stock
        ]);

        $data = [
            'product_vendors' => $productVendorUpdateData->toArray(),
        ];

        $response = $this->postJson(route('orders.store',$data));
        $response->assertStatus(201);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.user.id',$this->superAdmin->id)
            ->count('data.products_vendors', $productVendorsCount)
            ->etc()
        );

        $this->assertDatabaseHas('orders',[
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('products_vendors',
        [
           'vendor_id' => $vendor->vendor_id,
           'stock' => --$stock,
        ]);

        Bus::assertDispatched(LowStockEventJob::class);

    }

    /** @test */
    public function can_an_order_be_updated()
    {
        $productVendorUpdateData = ProductVendor::factory(2)->create();

        $data = [
            'product_vendors' => $productVendorUpdateData->toArray(),
        ];

        $order = $this->order;

        $response = $this->putJson(route('order.update', $order),$data);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('order_id',$order->order_id)
            ->where('user.id',$this->superAdmin->id)
            ->count('products_vendors',2)
            ->etc()
        );

        $this->assertDatabaseHas('orders_products',[
            'order_id' => $order->order_id,
            'product_vendor_id' => $productVendorUpdateData->first()->product_id,
        ]);
    }

}
