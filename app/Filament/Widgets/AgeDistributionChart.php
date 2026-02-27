<?php

namespace App\Filament\Widgets;

use App\Models\Audience;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AgeDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Faixa Etária dos Votantes';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $ages = Audience::query()
            ->whereNotNull('birth_date')
            ->select(DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18 THEN 'Menor de 18'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 24 THEN '18-24'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 55 THEN '55+'
                END as age_group
            "), DB::raw('count(*) as total'))
            ->groupBy('age_group')
            ->orderByRaw("FIELD(age_group, 'Menor de 18', '18-24', '25-34', '35-44', '45-54', '55+')")
            ->pluck('total', 'age_group');

        $groups = ['Menor de 18', '18-24', '25-34', '35-44', '45-54', '55+'];
        $data = [];
        foreach ($groups as $group) {
            $data[] = $ages[$group] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Votantes',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(107, 114, 128, 0.7)',
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $groups,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
