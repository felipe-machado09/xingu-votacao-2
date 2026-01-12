<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se a coluna existe
        if (Schema::hasColumn('companies', 'password')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('password')->nullable()->change();
            });
        } else {
            // Se não existir, criar como nullable
            Schema::table('companies', function (Blueprint $table) {
                $table->string('password')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não fazer nada no down, pois queremos manter nullable
    }
};
