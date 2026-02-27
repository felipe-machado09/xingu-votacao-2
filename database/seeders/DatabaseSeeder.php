<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '123456',
        ]);

        // Generate Shield permissions and assign super_admin
        $this->command->call('shield:generate', ['--all' => true]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
        $user->assignRole('super_admin');

        $this->call([
            CsvDataSeeder::class,
            LandingPageSectionSeeder::class,
        ]);
    }
}
