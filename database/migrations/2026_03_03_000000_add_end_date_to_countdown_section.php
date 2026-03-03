<?php

use App\Models\LandingPageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $section = LandingPageSection::where('key', 'countdown')->first();

        if ($section) {
            $metadata = $section->metadata ?? [];
            $metadata['end_date'] = '2026-03-15 23:59:59';
            $section->metadata = $metadata;
            $section->save();
        }
    }

    public function down(): void
    {
        $section = LandingPageSection::where('key', 'countdown')->first();

        if ($section) {
            $metadata = $section->metadata ?? [];
            unset($metadata['end_date']);
            $section->metadata = $metadata;
            $section->save();
        }
    }
};
