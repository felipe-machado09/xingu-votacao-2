<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AwardResource\Pages;
use App\Models\Award;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AwardResource extends Resource
{
    protected static ?string $model = Award::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Prêmios';

    protected static ?string $modelLabel = 'Prêmio';

    protected static ?string $pluralModelLabel = 'Prêmios';

    protected static string | \UnitEnum | null $navigationGroup = 'Sorteios';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Imagem')
                    ->image()
                    ->directory('award-images')
                    ->disk('public')
                    ->imageEditor()
                    ->maxSize(2048),
                Forms\Components\Select::make('tier')
                    ->label('Nível do Prêmio')
                    ->required()
                    ->options([
                        1 => 'Nível 1 - Básico (5 votos)',
                        2 => 'Nível 2 - Intermediário (15 votos)',
                        3 => 'Nível 3 - Máximo (Todos os votos)',
                    ])
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $minVotes = match ($state) {
                            1 => 5,
                            2 => 15,
                            3 => 999,
                            default => 5,
                        };
                        $set('min_votes', $minVotes);
                    }),
                Forms\Components\TextInput::make('min_votes')
                    ->label('Mínimo de Votos Necessários')
                    ->required()
                    ->numeric()
                    ->default(5)
                    ->minValue(1)
                    ->helperText('Número mínimo de votos que o participante precisa ter para concorrer a este prêmio'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Imagem')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('tier')
                    ->label('Nível')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        1 => 'info',
                        2 => 'warning',
                        3 => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Básico',
                        2 => 'Intermediário',
                        3 => 'Máximo',
                        default => 'N/A',
                    }),
                Tables\Columns\TextColumn::make('min_votes')
                    ->label('Min. Votos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('completed_draws_count')
                    ->counts('completedDraws')
                    ->label('Sorteados')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('remaining')
                    ->getStateUsing(fn (Award $record) => $record->remainingQuantity())
                    ->label('Restantes')
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
                Tables\Filters\SelectFilter::make('tier')
                    ->label('Nível')
                    ->options([
                        1 => 'Nível 1 - Básico',
                        2 => 'Nível 2 - Intermediário',
                        3 => 'Nível 3 - Máximo',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tier', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAwards::route('/'),
            'create' => Pages\CreateAward::route('/create'),
            'edit' => Pages\EditAward::route('/{record}/edit'),
        ];
    }
}
