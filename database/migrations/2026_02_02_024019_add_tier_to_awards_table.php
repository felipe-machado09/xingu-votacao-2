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
        Schema::table('awards', function (Blueprint $table) {
            $table->integer('tier')->default(1)->after('quantity')->comment('1=5 votos, 2=15 votos, 3=todos os votos');
            $table->integer('min_votes')->default(5)->after('tier')->comment('Mínimo de votos necessários');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('awards', function (Blueprint $table) {
            $table->dropColumn(['tier', 'min_votes']);
        });
    }
};
