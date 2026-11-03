<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Services\ExcelExport;
use App\Services\ReportYear;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('year')->label('Ano')->options(fn () => ReportYear::options())
                ->default(fn () => ReportYear::latest())->required()->selectablePlaceholder(false),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')->label('Extrair Excel')
                ->action(function () {
                    $year = (int) ($this->filters['year'] ?? ReportYear::latest());

                    return ExcelExport::download('painel-'.$year.'.xlsx', ['Ano', 'Categoria', 'Votos'],
                        Category::withCount(['votes' => fn ($query) => $query->forYear($year)])->get()
                            ->map(fn ($category) => [$year, $category->name, $category->votes_count]));
                }),
        ];
    }
}
