<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopCompaniesTable extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Top 10 Empresas Mais Votadas')
            ->query(
                Company::query()
                    ->withCount('votes')
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
                        '3' => 'orange',
                        default => 'primary',
                    }),
                
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Empresa')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('votes_count')
                    ->label('Total de Votos')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categorias')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated(false);
    }
}
