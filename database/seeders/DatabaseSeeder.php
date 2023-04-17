<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Basket;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Models\Variation;
use App\Models\VariationVendor;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
            'addressable_id' => $users->random()->user_id,
        ]);

        $permissions = collect(['delete', 'store', 'update', 'view', 'viewAny']);
        $permissions->each(fn ($permission) => Permission::create(['name' => $permission]));

        $mahdi = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => Hash::make('123456'),
            'email' => 'rahmanimahdi16@gmail.com',
        ]);
        $superAdmin = Role::create(['name' => config('auth.super_admin_role_name')]);
        $mahdi->assignRole($superAdmin);

        $parentCategories = Category::factory()->count(10)->create();
        $subCategories = Category::factory()->count(30)->create([
            'parent_category' => $parentCategories->random()->first()->category_id,
        ]);

        $brands = Brand::factory()->count(20)->create();
        $vendors = Vendor::factory()->count(20)->create();
        $sizes = Size::factory()->count(20)->create();
        $colors = Color::factory()->count(20)->create();
        $products = Product::factory()->count(20)
            ->sequence(fn (Sequence $sequence) => [
                'brand_id' => $brands->random()->brand_id,
                'category_id' => $subCategories->random()->category_id,
            ])
            ->create();

        $products->each(function ($product) use ($colors, $sizes, $vendors) {
            Variation::factory()
                ->count(20)->hasVariationVendor(2, [
                    'vendor_id' => $vendors->random()->vendor_id,
                ])
                ->create([
                    'product_id' => $product->product_id,
                    'size_id' => $sizes->random()->size_id,
                    'color_id' => $colors->random()->color_id,
                ]);
        });

//        $baskets = Basket::factory()->count(20)->create([
//            'user_id' => $users->random()->first()->user_id,
//            'total' => VariationVendor::inRandomOrder()->price,
//        ]);
//        $orders = Order::factory()->count(50)->create([
//            'user_id' => $users->random()->first()->user_id,
//        ]);
//
//        $orders->each(function (Order $order) use ($products) {
//            $order->products()->sync($products->random()->first()->product_id);
//        });
    }
}
