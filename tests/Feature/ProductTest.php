<?php

namespace Tests\Feature;

use App\Events\LowStockEvent;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_a_product_be_filtered()
    {
        $category = Category::factory()->create([
            'name' => 'test'
        ]);
        $categories = $category::factory(20)->create();
        $product = Product::factory()->create([
            'category_id' => $category->category_id,
            'name' => 'test',
        ]);
        Product::factory(100)->create();
        $vendor = Vendor::factory()->create();
        $product->vendors()->sync($vendor);
        $order = Order::factory()->create([
            'product_id' => $product->product_id,
            'vendor_id' => $vendor->vendor_id,
        ]);

        $response = $this->getJson(route('products.index',[
            'filter' => [
//                'category_id' => 1,
                'name' => 'Mrs'
            ]
        ]));

    }
}
