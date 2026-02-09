<?php

namespace Database\Factories;

use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsorFactory extends Factory
{
    protected $model = Sponsor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo_path' => null,
            'website' => fake()->url(),
            'is_active' => true,
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
