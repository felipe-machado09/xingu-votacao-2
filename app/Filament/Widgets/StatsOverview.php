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
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalVotes = Vote::count();
        $todayVotes = Vote::whereDate('created_at', today())->count();
        $yesterdayVotes = Vote::whereDate('created_at', today()->subDay())->count();
        $weekVotes = Vote::where('created_at', '>=', now()->startOfWeek())->count();
        $lastWeekVotes = Vote::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $monthVotes = Vote::where('created_at', '>=', now()->startOfMonth())->count();

        // Sparkline: votos por dia nos últimos 7 dias
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            return Vote::whereDate('created_at', today()->subDays($daysAgo))->count();
        })->toArray();

        // Trends
        $todayTrend = $yesterdayVotes > 0
            ? round((($todayVotes - $yesterdayVotes) / $yesterdayVotes) * 100)
            : ($todayVotes > 0 ? 100 : 0);

        $weekTrend = $lastWeekVotes > 0
            ? round((($weekVotes - $lastWeekVotes) / $lastWeekVotes) * 100)
            : ($weekVotes > 0 ? 100 : 0);

        $totalAudience = Audience::count();
        $newAudienceToday = Audience::whereDate('created_at', today())->count();

        $totalCompanies = Company::count();
        $activeCategories = Category::where('is_active', true)->count();
        $totalCategories = Category::count();

        $avgPerDay = round(Vote::where('created_at', '>=', now()->subDays(30))->count() / max(1, 30), 1);

        return [
            Stat::make('Total de Votos', number_format($totalVotes, 0, ',', '.'))
                ->description('Todos os votos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($last7Days),

            Stat::make('Votos Hoje', number_format($todayVotes, 0, ',', '.'))
                ->description(
                    $todayTrend >= 0
                        ? "+{$todayTrend}% vs ontem ({$yesterdayVotes})"
                        : "{$todayTrend}% vs ontem ({$yesterdayVotes})"
                )
                ->descriptionIcon($todayTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayTrend >= 0 ? 'success' : 'danger'),

            Stat::make('Esta Semana', number_format($weekVotes, 0, ',', '.'))
                ->description(
                    $weekTrend >= 0
                        ? "+{$weekTrend}% vs semana anterior"
                        : "{$weekTrend}% vs semana anterior"
                )
                ->descriptionIcon($weekTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($weekTrend >= 0 ? 'info' : 'danger'),

            Stat::make('Este Mês', number_format($monthVotes, 0, ',', '.'))
                ->description("Média: {$avgPerDay} votos/dia")
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Participantes', number_format($totalAudience, 0, ',', '.'))
                ->description($newAudienceToday > 0 ? "+{$newAudienceToday} novos hoje" : 'Cadastrados no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Empresas / Categorias', "{$totalCompanies} / {$totalCategories}")
                ->description("{$activeCategories} categorias ativas")
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray'),
        ];
    }
}
