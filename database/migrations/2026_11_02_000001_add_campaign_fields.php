<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('trade_name')->nullable();
            $table->string('instagram_url')->nullable();
        });
        DB::table('companies')->update(['trade_name' => DB::raw('legal_name')]);

        Schema::table('audiences', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->index();
        });
        DB::table('audiences')->update(['year' => 2025]);
        Schema::table('audiences', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable(false)->change();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->index('audience_id');
        });
        DB::table('votes')->update(['year' => 2025]);
        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable(false)->change();
            $table->dropUnique(['audience_id', 'category_id']);
            $table->unique(['audience_id', 'category_id', 'year']);
        });
        Schema::table('category_winners', function (Blueprint $table) {
            $table->index('category_id');
        });
        Schema::table('category_winners', function (Blueprint $table) {
            $table->dropUnique(['category_id']);
            $table->unique(['category_id', 'year']);
        });
        DB::table('category_winners')->update(['year' => 2025]);

        Schema::table('awards', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->index();
        });
        DB::table('awards')->update(['year' => 2025]);

        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->string('document_path')->nullable();
        });
        $countdown = DB::table('landing_page_sections')->where('key', 'countdown')->value('metadata');
        $metadata = $countdown ? json_decode($countdown, true, 512, JSON_THROW_ON_ERROR) : [];
        DB::table('landing_page_sections')->insertOrIgnore([
            'key' => 'campaign',
            'title' => 'Configurações da edição',
            'image' => DB::table('landing_page_sections')->where('key', 'hero')->where('is_active', true)->value('image'),
            'metadata' => json_encode([
                'year' => 2026,
                'end_date' => $metadata['end_date'] ?? null,
                'company_registration_enabled' => true,
                'randomize_companies' => true,
            ], JSON_THROW_ON_ERROR),
            'is_active' => true,
            'order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Annual records cannot be collapsed safely into the old lifetime constraints.
        if (DB::table('votes')->select('audience_id', 'category_id')->groupBy('audience_id', 'category_id')->havingRaw('COUNT(*) > 1')->exists()
            || DB::table('category_winners')->select('category_id')->groupBy('category_id')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('Há registros de várias edições. Exporte e revise os dados antes de reverter esta migração.');
        }
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique(['audience_id', 'category_id', 'year']);
            $table->unique(['audience_id', 'category_id']);
            $table->dropIndex(['year']);
            $table->dropColumn('year');
            $table->dropIndex(['audience_id']);
        });
        Schema::table('category_winners', function (Blueprint $table) {
            $table->dropUnique(['category_id', 'year']);
            $table->unique(['category_id']);
            $table->dropIndex(['category_id']);
        });
        Schema::table('landing_page_sections', fn (Blueprint $table) => $table->dropColumn('document_path'));
        Schema::table('audiences', function (Blueprint $table) {
            $table->dropIndex(['year']);
            $table->dropColumn('year');
        });
        Schema::table('awards', function (Blueprint $table) {
            $table->dropIndex(['year']);
            $table->dropColumn('year');
        });
        Schema::table('companies', fn (Blueprint $table) => $table->dropColumn(['trade_name', 'instagram_url']));
    }
};
