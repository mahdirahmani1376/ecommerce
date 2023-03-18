<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seo;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Factories\ProductFactory;
use Database\Factories\SeoFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = collect(['delete','store','update','view','viewAny']);
        $permissions->map(fn($permission) => Permission::create(['name' => $permission]));

        $mahdi = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => Hash::make('123456'),
            'email' => 'rahmanimahdi16@gmail.com',
        ]);
        $superAdmin = Role::create(['name' => config('auth.super_admin_role_name')]);
        $mahdi->assignRole($superAdmin);

        Product::factory()->count(20)->create();
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
