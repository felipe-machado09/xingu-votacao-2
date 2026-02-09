<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VotesOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Evolução de Votos (Últimos 7 dias)';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $votes = Vote::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d/m');
            $vote = $votes->firstWhere('date', $date);
            $counts[] = $vote ? $vote->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Votos',
                    'data' => $counts,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
