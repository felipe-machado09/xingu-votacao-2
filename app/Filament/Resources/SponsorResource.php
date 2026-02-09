<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SponsorResource\Pages;
use App\Filament\Resources\SponsorResource\RelationManagers;
use App\Models\Sponsor;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SponsorResource extends Resource
{
    protected static ?string $model = Sponsor::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Patrocinadores';

    protected static ?string $modelLabel = 'Patrocinador';

    protected static ?string $pluralModelLabel = 'Patrocinadores';

    protected static string | \UnitEnum | null $navigationGroup = 'Conteúdo';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo_path')
                    ->label('Logo')
                    ->image()
                    ->directory('sponsors')
                    ->disk('public')
                    ->imageEditor()
                    ->maxSize(20480),
                Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->url()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
                Forms\Components\TextInput::make('order')
                    ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->square()
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('Website')
                    ->searchable()
                    ->url(fn ($record) => $record->website, shouldOpenInNewTab: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSponsors::route('/'),
            'create' => Pages\CreateSponsor::route('/create'),
            'edit' => Pages\EditSponsor::route('/{record}/edit'),
        ];
    }
}
