<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Vote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopCompaniesTable extends BaseWidget
{
    use \App\Filament\Widgets\Concerns\FiltersVotesByYear;

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('🏆 Top 10 Empresas Mais Votadas')
            ->query(
                Company::query()
                    ->withCount(['votes' => fn ($query) => $query->forYear($this->reportYear())])
                    ->withCount('categories')
                    ->orderByDesc('votes_count')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ranking')
                    ->label('#')
                    ->state(
                        static function (Tables\Columns\Column $column): string {
                            return (string) (
                                (int) $column->getTable()->getRecords()->search(
                                    fn ($record) => $record === $column->getRecord()
                                ) + 1
                            );
                        }
                    )
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'gray',
                        '3' => 'danger',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => '🥇 1º',
                        '2' => '🥈 2º',
                        '3' => '🥉 3º',
                        default => $state . 'º',
                    }),

                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->display_name) . '&background=ef4444&color=fff&size=40'),

                Tables\Columns\TextColumn::make('trade_name')
                    ->label('Empresa')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->address_city ? "{$record->address_city}/{$record->address_state}" : null),

                Tables\Columns\TextColumn::make('votes_count')
                    ->label('Total de Votos')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('today_votes')
                    ->label('Hoje')
                    ->state(fn ($record) => Vote::forYear($this->reportYear())->where('company_id', $record->id)->whereDate('created_at', today())->count())
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray'),

                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Categorias')
                    ->badge()
                    ->color('warning'),
            ])
            ->paginated(false);
    }
}
