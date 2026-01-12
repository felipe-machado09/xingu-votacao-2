<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se as colunas já existem
        if (!Schema::hasColumn('companies', 'email')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('email')->nullable()->after('legal_name');
            });
        }

        if (!Schema::hasColumn('companies', 'password')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('password')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('companies', 'telefone')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('telefone')->nullable()->after('responsible_phone');
            });
        }

        if (!Schema::hasColumn('companies', 'whatsapp_number')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('whatsapp_number')->nullable()->after('telefone');
            });
        }

        if (!Schema::hasColumn('companies', 'lgpd_accepted')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->boolean('lgpd_accepted')->default(false)->after('whatsapp_number');
            });
        }

        if (!Schema::hasColumn('companies', 'role_name')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('role_name')->default('Empresa')->after('lgpd_accepted');
            });
        }

        if (!Schema::hasColumn('companies', 'registration_complete')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->boolean('registration_complete')->default(false)->after('role_name');
            });
        }

        // Popular emails únicos para empresas existentes sem email
        \DB::statement('UPDATE companies SET email = CONCAT("empresa_", id, "@temp.com") WHERE email IS NULL OR email = ""');

        // Adicionar unique constraint se não existir
        if (Schema::hasColumn('companies', 'email')) {
            try {
                Schema::table('companies', function (Blueprint $table) {
                    $table->string('email')->unique()->change();
                });
            } catch (\Exception $e) {
                // Constraint já existe, ignorar
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'password',
                'telefone',
                'whatsapp_number',
                'lgpd_accepted',
                'role_name',
                'registration_complete'
            ]);
        });
    }
};
