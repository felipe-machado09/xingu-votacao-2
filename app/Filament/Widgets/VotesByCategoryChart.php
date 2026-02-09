<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VotesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Votos por Categoria';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $categories = Category::withCount('votes')
            ->orderByDesc('votes_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Votos',
                    'data' => $categories->pluck('votes_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(245, 158, 11, 0.5)',
                        'rgba(239, 68, 68, 0.5)',
                        'rgba(139, 92, 246, 0.5)',
                        'rgba(236, 72, 153, 0.5)',
                        'rgba(20, 184, 166, 0.5)',
                        'rgba(251, 146, 60, 0.5)',
                        'rgba(34, 197, 94, 0.5)',
                        'rgba(168, 85, 247, 0.5)',
                    ],
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
