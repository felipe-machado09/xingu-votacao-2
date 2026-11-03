<?php

namespace App\Filament\Widgets\Concerns;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

trait FiltersVotesByYear
{
    use InteractsWithPageFilters;

    protected function reportYear(): int
    {
        return (int) ($this->pageFilters['year'] ?? \App\Services\ReportYear::latest());
    }
}
