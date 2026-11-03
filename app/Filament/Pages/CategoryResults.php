<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use App\Models\Vote;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CategoryResults extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Resultados';

    protected static ?string $title = 'Resultados por Categoria';

    protected static string | \UnitEnum | null $navigationGroup = 'Relatórios';

    protected string $view = 'filament.pages.category-results';

    protected static ?int $navigationSort = 1;

    public ?int $selectedCategoryId = null;

    public int $year;

    public function mount(): void
    {
        $this->year = \App\Services\ReportYear::latest();
        $this->selectedCategoryId = request()->get('category_id');
    }

    public function selectCategory(int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Category::query()
                    ->withCount(['votes' => fn ($query) => $query->forYear($this->year)])
                    ->orderBy('category_group')
                    ->orderBy('name')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Categoria')
                    ->searchable()
                    ->description(fn (Category $record) => $record->description),
                TextColumn::make('category_group')
                    ->label('Grupo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Comércio' => 'success',
                        'Serviços' => 'info',
                        'Indústria' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('votes_count')
                    ->label('Total de Votos')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('winner')
                    ->label('Vencedor Atual')
                    ->getStateUsing(function (Category $record) {
                        $winner = CategoryWinner::where('category_id', $record->id)
                            ->where('year', $this->year)
                            ->with('company')
                            ->first();
                        return $winner ? $winner->company->display_name : 'Não definido';
                    })
                    ->icon(fn (Category $record) => CategoryWinner::where('category_id', $record->id)->where('year', $this->year)->exists() ? 'heroicon-o-trophy' : null)
                    ->iconColor('warning'),
            ])
            ->actions([
                \Filament\Actions\Action::make('viewResults')
                    ->label('Ver Top 10')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->action(function (Category $record) {
                        $this->selectedCategoryId = $record->id;
                    }),
            ])
            ->defaultSort('votes_count', 'desc');
    }

    public function getTopCompanies(): array
    {
        if (!$this->selectedCategoryId) {
            return [];
        }

        return Vote::where('category_id', $this->selectedCategoryId)
            ->forYear($this->year)
            ->select('company_id', DB::raw('COUNT(*) as votes'))
            ->groupBy('company_id')
            ->orderByDesc('votes')
            ->limit(10)
            ->get()
            ->map(function ($vote) {
                $company = Company::find($vote->company_id);
                return [
                    'company_id' => $vote->company_id,
                    'company_name' => $company ? $company->display_name : 'N/A',
                    'votes' => $vote->votes,
                ];
            })
            ->toArray();
    }

    public function getCurrentWinner(): ?CategoryWinner
    {
        if (!$this->selectedCategoryId) {
            return null;
        }

        return CategoryWinner::with('company')
            ->where('year', $this->year)
            ->where('category_id', $this->selectedCategoryId)
            ->first();
    }

    public function getSelectedCategory(): ?Category
    {
        if (!$this->selectedCategoryId) {
            return null;
        }

        return Category::find($this->selectedCategoryId);
    }

    public function setWinner(int $companyId): void
    {
        if (!$this->selectedCategoryId) {
            Notification::make()
                ->title('Erro ao definir vencedor')
                ->body('Categoria não selecionada')
                ->danger()
                ->send();
            return;
        }

        if (! Vote::forYear($this->year)->where('category_id', $this->selectedCategoryId)->where('company_id', $companyId)->exists()) {
            Notification::make()->title('Esta empresa não possui votos nesta categoria e edição.')->danger()->send();
            return;
        }

        CategoryWinner::updateOrCreate(
            ['category_id' => $this->selectedCategoryId, 'year' => $this->year],
            [
                'company_id' => $companyId,
                'chosen_at' => now(),
            ]
        );

        Notification::make()
            ->title('Vencedor definido com sucesso')
            ->success()
            ->send();

        $this->selectedCategoryId = null;
    }

    public function closeResults(): void
    {
        $this->selectedCategoryId = null;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('exportExcel')->label('Extrair Excel')
                ->action(fn () => \App\Services\ExcelExport::download(
                    'resultados-'.$this->year.'.xlsx',
                    ['Ano', 'Categoria', 'Empresa', 'Votos'],
                    Vote::forYear($this->year)->select('category_id', 'company_id', DB::raw('COUNT(*) as total'))
                        ->groupBy('category_id', 'company_id')->orderBy('category_id')->orderByDesc('total')
                        ->with(['category', 'company'])->get()
                        ->map(fn ($vote) => [$this->year, $vote->category->name, $vote->company->display_name, $vote->total])
                )),
        ];
    }
}
