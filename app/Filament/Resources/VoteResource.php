<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoteResource\Pages;
use App\Models\Vote;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VoteResource extends Resource
{
    protected static ?string $model = Vote::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-hand-thumb-up';

    protected static ?string $navigationLabel = 'Votos';

    protected static ?string $modelLabel = 'Voto';

    protected static ?string $pluralModelLabel = 'Votos';

    protected static string | \UnitEnum | null $navigationGroup = 'Votação';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('audience.name')
                    ->label('Participante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('company.legal_name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data do Voto')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('year')->label('Edição')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Ano')
                    ->options(fn () => \App\Services\ReportYear::options())
                    ->default(fn () => \App\Services\ReportYear::latest())
                    ->query(fn ($query, array $data) => $query->forYear((int) ($data['value'] ?: \App\Services\ReportYear::latest()))),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoria')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('company')
                    ->label('Empresa')
                    ->relationship('company', 'legal_name'),
            ])
            ->actions([
                Actions\ViewAction::make(),
            ])
            ->headerActions([
                Actions\Action::make('exportExcel')->label('Extrair Excel')->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($livewire) => \App\Services\ExcelExport::download(
                        'votos.xlsx',
                        ['Edição', 'Participante', 'Categoria', 'Empresa', 'Data do voto'],
                        $livewire->getFilteredSortedTableQuery()->with(['audience', 'category', 'company'])->lazy()
                            ->map(fn (Vote $vote) => [$vote->year, $vote->audience->name, $vote->category->name, $vote->company->display_name, $vote->created_at->format('d/m/Y H:i')])
                    )),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVotes::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
