<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VotesOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Evolução de Votos (Últimos 30 dias)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $votes = Vote::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $dates = [];
        $counts = [];
        $cumulative = [];
        $runningTotal = 0;

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d/m');
            $count = $votes[$date] ?? 0;
            $counts[] = $count;
            $runningTotal += $count;
            $cumulative[] = $runningTotal;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Votos por dia',
                    'data' => $counts,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.08)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 2,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Acumulado no período',
                    'data' => $cumulative,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'transparent',
                    'borderDash' => [5, 5],
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'borderWidth' => 1.5,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['stepSize' => 1],
                ],
                'y1' => [
                    'position' => 'right',
                    'beginAtZero' => true,
                    'grid' => ['display' => false],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
