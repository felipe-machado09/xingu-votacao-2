<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('award_draws', function (Blueprint $table) {
            $table->boolean('show_on_home')->default(false)->after('collected_at');
        });
    }

    public function down(): void
    {
        Schema::table('award_draws', function (Blueprint $table) {
            $table->dropColumn('show_on_home');
        });
    }
};
