<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VotesByHourChart extends ChartWidget
{
    protected ?string $heading = 'Horários de Pico (Votos por Hora)';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $votes = Vote::query()
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        $labels = [];
        $data = [];

        for ($h = 0; $h < 24; $h++) {
            $labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . 'h';
            $data[] = $votes[$h] ?? 0;
        }

        // Highlight peak hour
        $maxVotes = max($data ?: [0]);
        $colors = array_map(function ($val) use ($maxVotes) {
            if ($maxVotes > 0 && $val === $maxVotes) {
                return 'rgba(239, 68, 68, 0.85)';
            }
            return 'rgba(59, 130, 246, 0.6)';
        }, $data);

        return [
            'datasets' => [
                [
                    'label' => 'Votos',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderRadius' => 4,
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
