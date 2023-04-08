<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    protected User $superAdmin;

    protected function SetUp(): void
    {
        parent::SetUp();
        $superAdmin = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => '123456',
            'email' => 'rahmanimahdi16@gmail.com',
        ]);

        $superAdminRole = Role::create([
            'name' => config('auth.super_admin_role_name'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        $admin = User::factory()->create();

        $adminRole = Role::create([
            'name' => config('auth.admin_role_name'),
        ]);
        $admin->assignRole($adminRole);

        $this->superAdmin = $superAdmin;
        $this->admin = $admin;

        $this->actingAs($superAdmin);
    }
}
