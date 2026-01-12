<?php

namespace App\Filament\Widgets;

use App\Models\Audience;
use App\Models\Category;
use App\Models\Company;
use App\Models\Vote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Votos', Vote::count())
                ->description('Votos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 12, 18, 25, 32, 40, Vote::count()]),
            
            Stat::make('Participantes', Audience::count())
                ->description('Cadastrados no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Empresas', Company::count())
                ->description('Participando')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
            
            Stat::make('Categorias', Category::count())
                ->description(Category::where('is_active', true)->count() . ' ativas')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),
        ];
    }
}
