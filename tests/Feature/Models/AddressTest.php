<?php

namespace Tests\Feature\Models;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class AddressTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_an_address_be_assigned_to_a_user()
    {
        $user = User::factory()->create();
        $address = Address::factory()->make();
        $response = $this->actingAs($user)->postJson(route('address.store'),$address->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('addresses',[
            'address' => $address->address,
        ]);
    }
}
