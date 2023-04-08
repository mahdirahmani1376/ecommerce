<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_a_product_be_filtered()
    {
        $category = Category::factory()->create([
            'name' => 'test',
        ]);
        $categories = $category::factory(20)->create();
        $product = Product::factory()->create([
            'category_id' => $category->category_id,
            'name' => 'test',
        ]);
        Product::factory(100)->has(Brand::factory(), 'brand')->create();
        $vendor = Vendor::factory()->create();

        $productVendor = ProductVendor::factory()->create([
            'vendor_id' => $vendor->vendor_id,
            'product_id' => $product->product_id,
        ]);

        $user = $this->superAdmin;
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('products.index', [
            'filter' => [
                //                'category_id' => 1,
                'name' => 'Mrs',
            ],
        ]));
    }
}
