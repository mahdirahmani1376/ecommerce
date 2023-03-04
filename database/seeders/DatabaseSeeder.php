<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $mahdi = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => Hash::make('123456'),
            'email' => 'rahmanimahdi16@gmail.com',
        ]);
        $superAdmin = Role::create(['name' => config('auth.super_admin_role_name')]);
        $mahdi->assignRole($superAdmin);
    }
}
