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

    public function mount(): void
    {
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
                    ->where('is_active', true)
                    ->withCount('votes')
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
                            ->with('company')
                            ->first();
                        return $winner ? $winner->company->legal_name : 'Não definido';
                    })
                    ->icon(fn (Category $record) => CategoryWinner::where('category_id', $record->id)->exists() ? 'heroicon-o-trophy' : null)
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
            ->select('company_id', DB::raw('COUNT(*) as votes'))
            ->groupBy('company_id')
            ->orderByDesc('votes')
            ->limit(10)
            ->get()
            ->map(function ($vote) {
                $company = Company::find($vote->company_id);
                return [
                    'company_id' => $vote->company_id,
                    'company_name' => $company ? $company->legal_name : 'N/A',
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

        CategoryWinner::updateOrCreate(
            ['category_id' => $this->selectedCategoryId],
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
}
