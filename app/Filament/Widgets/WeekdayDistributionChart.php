<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class WeekdayDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Votos por Dia da Semana';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $votes = Vote::query()
            ->select(DB::raw('DAYOFWEEK(created_at) as weekday'), DB::raw('count(*) as count'))
            ->groupBy('weekday')
            ->orderBy('weekday')
            ->pluck('count', 'weekday');

        // DAYOFWEEK: 1=Sunday, 2=Monday, ..., 7=Saturday
        $dayNames = [
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta',
            7 => 'Sábado',
            1 => 'Domingo',
        ];

        $labels = [];
        $data = [];
        $colors = [
            'rgba(239, 68, 68, 0.7)',
            'rgba(59, 130, 246, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(139, 92, 246, 0.7)',
            'rgba(236, 72, 153, 0.7)',
            'rgba(20, 184, 166, 0.7)',
        ];

        $i = 0;
        foreach ($dayNames as $num => $name) {
            $labels[] = $name;
            $data[] = $votes[$num] ?? 0;
            $i++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Votos',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
