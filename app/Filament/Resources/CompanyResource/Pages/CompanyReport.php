<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Vote;
use Filament\Resources\Pages\Page as ResourcePage;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompanyReport extends ResourcePage
{
    use InteractsWithRecord;

    protected static string $resource = CompanyResource::class;

    protected string $view = 'filament.pages.company-report';

    protected static ?string $title = 'Relatório de Votação';

    public ?string $period = '30'; // days
    public int $year;

    public function mount(int | string $record): void
    {
        $this->year = \App\Services\ReportYear::latest();
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('exportExcel')->label('Extrair Excel')
                ->action(fn () => \App\Services\ExcelExport::download(
                    'empresa-'.$this->record->id.'-'.$this->year.'.xlsx',
                    ['Ano', 'Categoria', 'Votos', 'Total da categoria', 'Posição', 'Percentual'],
                    collect($this->getCategoryRankings())->map(fn ($row) => [
                        $this->year, $row['category'], $row['votes'], $row['total_votes'], $row['position'], $row['percentage'],
                    ])
                )),
        ];
    }

    public function updatedPeriod(): void
    {
        // Livewire reactive update
    }

    // ==================== STATS ====================

    public function getTotalVotesProperty(): int
    {
        return $this->record->votes()->forYear($this->year)->count();
    }

    public function getPeriodVotesProperty(): int
    {
        return $this->record->votes()->forYear($this->year)
            ->where('created_at', '>=', now()->subDays((int) $this->period))
            ->count();
    }

    public function getTodayVotesProperty(): int
    {
        return $this->record->votes()->forYear($this->year)
            ->whereDate('created_at', today())
            ->count();
    }

    public function getWeekVotesProperty(): int
    {
        return $this->record->votes()->forYear($this->year)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
    }

    public function getMonthVotesProperty(): int
    {
        return $this->record->votes()->forYear($this->year)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
    }

    public function getCategoriesCountProperty(): int
    {
        return $this->record->categories()->count();
    }

    public function getUniqueVotersProperty(): int
    {
        return $this->record->votes()->forYear($this->year)
            ->distinct('audience_id')
            ->count('audience_id');
    }

    public function getAverageVotesPerDayProperty(): float
    {
        $firstVote = $this->record->votes()->forYear($this->year)->min('created_at');
        if (!$firstVote) return 0;

        $days = Carbon::parse($firstVote)->diffInDays(now()) + 1;
        return round($this->totalVotes / $days, 1);
    }

    // ==================== CHARTS DATA ====================

    public function getVotesPerDayChart(): array
    {
        $days = (int) $this->period;
        $votes = $this->record->votes()->forYear($this->year)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $data[] = $votes[$date] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getVotesPerWeekChart(): array
    {
        $votes = $this->record->votes()->forYear($this->year)
            ->select(
                DB::raw('YEARWEEK(created_at, 1) as week'),
                DB::raw('MIN(DATE(created_at)) as week_start'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subWeeks(12))
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        $labels = $votes->map(fn ($v) => Carbon::parse($v->week_start)->format('d/m'))->toArray();
        $data = $votes->pluck('count')->toArray();

        return ['labels' => $labels, 'data' => $data];
    }

    public function getVotesPerMonthChart(): array
    {
        $votes = $this->record->votes()->forYear($this->year)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [
            '01' => 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Abr',
            '05' => 'Mai', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
            '09' => 'Set', '10' => 'Out', '11' => 'Nov', '12' => 'Dez'
        ];

        $labels = $votes->map(function ($v) use ($months) {
            $parts = explode('-', $v->month);
            return ($months[$parts[1]] ?? $parts[1]) . '/' . $parts[0];
        })->toArray();
        $data = $votes->pluck('count')->toArray();

        return ['labels' => $labels, 'data' => $data];
    }

    public function getVotesByHourChart(): array
    {
        $votes = $this->record->votes()->forYear($this->year)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $labels = [];
        $data = [];
        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $data[] = $votes[$h] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getVotesByCategoryChart(): array
    {
        $votes = $this->record->votes()->forYear($this->year)
            ->join('categories', 'votes.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.name')
            ->orderByDesc('count')
            ->get();

        return [
            'labels' => $votes->pluck('name')->toArray(),
            'data' => $votes->pluck('count')->toArray(),
        ];
    }

    // ==================== AGE DEMOGRAPHICS ====================

    public function getAgeDistribution(): array
    {
        $voters = $this->record->votes()->forYear($this->year)
            ->join('audiences', 'votes.audience_id', '=', 'audiences.id')
            ->whereNotNull('audiences.birth_date')
            ->select('audiences.birth_date')
            ->distinct('audiences.id')
            ->get();

        $ranges = [
            '16-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55-64' => 0,
            '65+' => 0,
        ];

        foreach ($voters as $voter) {
            $age = Carbon::parse($voter->birth_date)->age;
            if ($age >= 65) $ranges['65+']++;
            elseif ($age >= 55) $ranges['55-64']++;
            elseif ($age >= 45) $ranges['45-54']++;
            elseif ($age >= 35) $ranges['35-44']++;
            elseif ($age >= 25) $ranges['25-34']++;
            else $ranges['16-24']++;
        }

        return [
            'labels' => array_keys($ranges),
            'data' => array_values($ranges),
        ];
    }

    // ==================== RANKING ====================

    public function getCategoryRankings(): array
    {
        $categories = $this->record->categories()->get();
        $rankings = [];

        foreach ($categories as $category) {
            $companyVotes = Vote::forYear($this->year)->where('category_id', $category->id)
                ->where('company_id', $this->record->id)
                ->count();

            $totalCategoryVotes = Vote::forYear($this->year)->where('category_id', $category->id)->count();

            // Get position
            $position = Vote::forYear($this->year)->where('category_id', $category->id)
                ->select('company_id', DB::raw('COUNT(*) as votes'))
                ->groupBy('company_id')
                ->orderByDesc('votes')
                ->get()
                ->search(fn ($item) => $item->company_id === $this->record->id);

            $rankings[] = [
                'category' => $category->name,
                'votes' => $companyVotes,
                'total_votes' => $totalCategoryVotes,
                'position' => $position !== false ? $position + 1 : '-',
                'percentage' => $totalCategoryVotes > 0
                    ? round(($companyVotes / $totalCategoryVotes) * 100, 1)
                    : 0,
            ];
        }

        return collect($rankings)->sortBy('position')->values()->toArray();
    }

    // ==================== RECENT VOTES ====================

    public function getRecentVotes(): \Illuminate\Support\Collection
    {
        return $this->record->votes()->forYear($this->year)
            ->with(['audience', 'category'])
            ->latest('created_at')
            ->limit(20)
            ->get();
    }

    // ==================== WEEKDAY DISTRIBUTION ====================

    public function getWeekdayDistribution(): array
    {
        $votes = $this->record->votes()->forYear($this->year)
            ->select(DB::raw('DAYOFWEEK(created_at) as dow'), DB::raw('COUNT(*) as count'))
            ->groupBy('dow')
            ->orderBy('dow')
            ->pluck('count', 'dow')
            ->toArray();

        $days = [1 => 'Dom', 2 => 'Seg', 3 => 'Ter', 4 => 'Qua', 5 => 'Qui', 6 => 'Sex', 7 => 'Sáb'];
        $labels = [];
        $data = [];

        foreach ($days as $num => $name) {
            $labels[] = $name;
            $data[] = $votes[$num] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
