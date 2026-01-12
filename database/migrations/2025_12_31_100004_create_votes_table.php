<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audience_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ip_hash')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');
            
            $table->unique(['audience_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
