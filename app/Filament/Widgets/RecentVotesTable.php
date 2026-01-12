<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentVotesTable extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimos Votos Registrados')
            ->query(
                Vote::query()
                    ->with(['audience', 'category', 'company'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('audience.name')
                    ->label('Participante')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('company.legal_name')
                    ->label('Empresa Votada')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
