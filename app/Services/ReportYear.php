<?php

namespace App\Services;

use App\Models\LandingPageSection;
use App\Models\Vote;

class ReportYear
{
    public static function options(): array
    {
        return Vote::query()->distinct()->pluck('year')
            ->push(LandingPageSection::campaignYear())
            ->unique()->sortDesc()->mapWithKeys(fn ($year) => [$year => $year])->all();
    }

    public static function latest(): int
    {
        return (int) array_key_first(static::options());
    }

    public static function fromRequest(): int
    {
        $data = request()->validate(['year' => ['nullable', 'integer', 'between:2000,2100']]);

        return (int) ($data['year'] ?? static::latest());
    }
}
