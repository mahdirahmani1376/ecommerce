<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\StockEnum;
use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVendor;
use App\Models\User;
use App\Models\Vendor;
use Database\Factories\ProductFactory;
use Database\Factories\SeoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $users = User::factory()->count(20)->create();
        $users->each(function (User $user) {
           $user->assignRole('user');
        });

        $addresses = Address::factory()->count(30)->create([
            'addressable_type' => User::class,
            'addressable_id' => $users->random()->id,
        ]);

        $permissions = collect(['delete','store','update','view','viewAny']);
        $permissions->map(fn($permission) => Permission::create(['name' => $permission]));

        $mahdi = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => Hash::make('123456'),
            'email' => 'rahmanimahdi16@gmail.com',
        ]);
        $superAdmin = Role::create(['name' => config('auth.super_admin_role_name')]);
        $mahdi->assignRole($superAdmin);

        $parentCategories = Category::factory()->count(10)->create();
        $subCategories = Category::factory()->count(30)->create([
            'parent_category' => $parentCategories->random()->first()->category_id
        ]);

        $brands = Brand::factory()->count(20)->create();
        $vendors = Vendor::factory()->count(20)->create();
        $products = Product::factory()->count(80)->create([
            'brand_id' => $brands->random()->first()->brand_id,
            'category_id' => $subCategories->random()->first()->category_id,
        ]);
        $productVendor = ProductVendor::factory()->count(100)->create([
            'vendor_id' => $vendors->random()->first()->vendor_id,
            'product_id' => $products->random()->first()->product_id,
        ]);

        $orders = Order::factory()->count(50)->create([
            'user_id' => $users->random()->first()->id,
        ]);

        $orders->each(function (Order $order) use ($products) {
            $order->products()->sync($products->random()->first()->product_id);
        });


    }
}
