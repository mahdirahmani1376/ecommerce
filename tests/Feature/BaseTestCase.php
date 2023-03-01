<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    protected function SetUp(): void
    {
        parent::SetUp();
        $superAdmin = User::factory()->create([
            'name' => 'mahdi rahmani',
            'password' => '123456',
            'email' => 'rahmanimahdi16@gmail.com',
        ]);
        $this->actingAs($superAdmin);
    }
}
