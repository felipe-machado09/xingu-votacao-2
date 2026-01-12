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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('main_image_path')->nullable()->after('logo_path');
            $table->string('facade_image_path')->nullable()->after('main_image_path');
            $table->string('team_image_path')->nullable()->after('facade_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['main_image_path', 'facade_image_path', 'team_image_path']);
        });
    }
};
