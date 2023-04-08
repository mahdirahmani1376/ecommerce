<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
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
    }

    /** @test */
    public function can_user_recieve_token()
    {
        $response = $this->actingAs($this->superAdmin)->postJson(route('tokens.create'));

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('message')
            ->has('token')
            ->etc()
        );
    }
}
