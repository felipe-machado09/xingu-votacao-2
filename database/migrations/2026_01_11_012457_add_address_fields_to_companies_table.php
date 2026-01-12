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
            $table->string('address_street')->nullable()->after('whatsapp_number');
            $table->string('address_number')->nullable()->after('address_street');
            $table->string('address_complement')->nullable()->after('address_number');
            $table->string('address_neighborhood')->nullable()->after('address_complement');
            $table->string('address_city')->nullable()->after('address_neighborhood');
            $table->string('address_state')->nullable()->after('address_city');
            $table->string('address_zipcode')->nullable()->after('address_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'address_street',
                'address_number',
                'address_complement',
                'address_neighborhood',
                'address_city',
                'address_state',
                'address_zipcode',
            ]);
        });
    }
};
