<?php

namespace Database\Factories;

use App\Models\Award;
use Illuminate\Database\Eloquent\Factories\Factory;

class AwardFactory extends Factory
{
    protected $model = Award::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'image_path' => null,
            'quantity' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
