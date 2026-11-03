<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'legal_name' => fake()->unique()->company(),
            'trade_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'cnpj' => fake()->unique()->numerify('##############'),
            'responsible_name' => fake()->name(),
            'responsible_phone' => '93999999999',
            'whatsapp_number' => '93999999999',
        ];
    }
}
