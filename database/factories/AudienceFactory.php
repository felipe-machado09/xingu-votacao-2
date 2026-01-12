<?php

namespace Database\Factories;

use App\Models\Audience;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudienceFactory extends Factory
{
    protected $model = Audience::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date('Y-m-d', '-18 years'),
            'phone' => fake()->numerify('###########'),
            'email_verified_at' => null,
        ];
    }
}
