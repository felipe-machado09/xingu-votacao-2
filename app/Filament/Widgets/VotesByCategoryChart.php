<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class VotesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Votos por Categoria';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '280px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = Category::withCount('votes')
            ->orderByDesc('votes_count')
            ->limit(12)
            ->get();

        $totalVotes = $categories->sum('votes_count');

        $labels = $categories->map(function ($cat) use ($totalVotes) {
            $pct = $totalVotes > 0 ? round(($cat->votes_count / $totalVotes) * 100, 1) : 0;
            return "{$cat->name} ({$pct}%)";
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Votos',
                    'data' => $categories->pluck('votes_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(20, 184, 166, 0.7)',
                        'rgba(251, 146, 60, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(14, 165, 233, 0.7)',
                        'rgba(244, 63, 94, 0.7)',
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 8,
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'cutout' => '55%',
        ];
    }
}
