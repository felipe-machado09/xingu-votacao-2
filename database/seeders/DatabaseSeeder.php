<?php

namespace Database\Seeders;

use App\Models\Audience;
use App\Models\Award;
use App\Models\Category;
use App\Models\Company;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            CategorySeeder::class,
        ]);

        $categories = Category::all();
        $companies = Company::factory(20)->create();
        
        foreach ($companies as $company) {
            $company->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')
            );
        }

        Award::factory(10)->create();

        Audience::factory(50)->create([
            'email_verified_at' => now(),
        ]);

        // Criar patrocinadores de exemplo
        Sponsor::create([
            'name' => 'Patrocinador Premium',
            'website' => 'https://example.com',
            'is_active' => true,
            'order' => 1,
        ]);

        Sponsor::create([
            'name' => 'Patrocinador Ouro',
            'website' => 'https://example2.com',
            'is_active' => true,
            'order' => 2,
        ]);

        Sponsor::create([
            'name' => 'Patrocinador Prata',
            'website' => 'https://example3.com',
            'is_active' => true,
            'order' => 3,
        ]);

        $this->call([
            LandingPageSectionSeeder::class,
        ]);
    }
}
