<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Size;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariationFactory extends Factory
{
    protected $model = Variation::class;

    public function definition(): array
    {
        return [
            'size_id' => Size::factory(),
            'color_id' => Color::factory(),
        ];
    }
}
