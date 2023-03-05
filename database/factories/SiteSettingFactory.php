<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logo' => fake()->text,
            'support_phone' => fake()->text,
            'phone_one' => fake()->phoneNumber,
            'email' => fake()->email,
            'company_address' => fake()->address,
            'facebook' => fake()->userName,
            'twitter' => fake()->userName,
        ];
    }
}
