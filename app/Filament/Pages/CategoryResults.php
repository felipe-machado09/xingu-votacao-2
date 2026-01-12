<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\CategoryWinner;
use App\Models\Company;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CategoryResults extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Resultados';

    protected static ?string $title = 'Resultados por Categoria';

    protected static ?string $navigationGroup = 'Relatórios';

    protected static string $view = 'filament.pages.category-results';

    protected static ?int $navigationSort = 1;

    public ?int $selectedCategoryId = null;

    public function mount(): void
    {
        $this->form->fill([
            'category_id' => $this->selectedCategoryId,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Select Category')
                    ->options(Category::pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedCategoryId = $state;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Company::query()
                    ->when($this->selectedCategoryId, function (Builder $query) {
                        $query->whereHas('categories', function (Builder $q) {
                            $q->where('categories.id', $this->selectedCategoryId);
                        });
                    })
                    ->withCount([
                        'votes' => function (Builder $query) {
                            if ($this->selectedCategoryId) {
                                $query->where('category_id', $this->selectedCategoryId);
                            }
                        }
                    ])
                    ->orderByDesc('votes_count')
            )
            ->columns([
                TextColumn::make('legal_name')
                    ->label('Company')
                    ->searchable(),
                TextColumn::make('votes_count')
                    ->label('Votes')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('setWinner')
                    ->label('Set as Winner')
                    ->icon('heroicon-o-trophy')
                    ->requiresConfirmation()
                    ->visible(fn () => $this->selectedCategoryId !== null)
                    ->action(function (Company $record) {
                        if (!$this->selectedCategoryId) {
                            Notification::make()
                                ->title('Please select a category first')
                                ->warning()
                                ->send();
                            return;
                        }

                        CategoryWinner::updateOrCreate(
                            ['category_id' => $this->selectedCategoryId],
                            [
                                'company_id' => $record->id,
                                'chosen_at' => now(),
                            ]
                        );

                        Notification::make()
                            ->title('Winner set successfully')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [];
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
}
