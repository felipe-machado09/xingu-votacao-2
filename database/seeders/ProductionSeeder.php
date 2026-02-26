<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // Gerar todas as permissões do Filament Shield
        $this->command->call('shield:generate', ['--all' => true]);
        $this->command->info('✅ Permissões do Shield geradas');

        // Criar role de super_admin se não existir
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => 'web']
        );

        // Atribuir TODAS as permissões ao super_admin
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);
        $this->command->info('✅ Todas as permissões atribuídas ao super_admin');

        // Criar usuário admin
        $admin = User::firstOrCreate(
            ['email' => 'felipe_machado09@hotmail.com'],
            [
                'name' => 'Felipe Admin',
                'password' => Hash::make('mudar@123'),
            ]
        );

        // Atribuir role de super_admin
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        $this->command->info('✅ Usuário admin criado: felipe_machado09@hotmail.com');
        $this->command->warn('⚠️  Senha temporária: mudar@123 - ALTERE IMEDIATAMENTE!');

        // Seeder das seções da landing page
        $this->call([
            LandingPageSectionSeeder::class,
        ]);

        $this->command->info('✅ Seções da landing page criadas');

        // Importar categorias e empresas dos CSVs
        $this->call([
            CsvDataSeeder::class,
        ]);

        $this->command->info('✅ Categorias e empresas importadas dos CSVs');
    }
}
