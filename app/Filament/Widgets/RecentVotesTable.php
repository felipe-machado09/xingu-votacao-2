<?php

namespace App\Filament\Widgets;

use App\Models\Vote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentVotesTable extends BaseWidget
{
    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('🗳️ Últimos 20 Votos Registrados')
            ->query(
                Vote::query()
                    ->with(['audience', 'category', 'company'])
                    ->latest('created_at')
                    ->limit(20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('audience.name')
                    ->label('Participante')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->audience?->email),

                Tables\Columns\TextColumn::make('audience.birth_date')
                    ->label('Idade')
                    ->formatStateUsing(fn ($state) => $state ? $state->age . ' anos' : '-')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('company.legal_name')
                    ->label('Empresa Votada')
                    ->searchable()
                    ->weight('bold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at?->diffForHumans())
                    ->color('success'),
            ])
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }
}
