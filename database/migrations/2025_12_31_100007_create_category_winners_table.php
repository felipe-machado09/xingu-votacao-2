<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamp('chosen_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_winners');
    }
};
