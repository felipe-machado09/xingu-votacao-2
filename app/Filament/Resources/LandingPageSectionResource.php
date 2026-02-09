<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageSectionResource\Pages;
use App\Filament\Resources\LandingPageSectionResource\RelationManagers;
use App\Models\LandingPageSection;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LandingPageSectionResource extends Resource
{
    protected static ?string $model = LandingPageSection::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Seções da Landing';

    protected static ?string $modelLabel = 'Seção';

    protected static ?string $pluralModelLabel = 'Seções';

    protected static string | \UnitEnum | null $navigationGroup = 'Conteúdo';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Chave')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Identificador único da seção (ex: hero, about, stats)'),
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->label('Conteúdo')
                    ->rows(5)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('Imagem')
                    ->image()
                    ->directory('landing-sections')
                    ->disk('public')
                    ->imageEditor()
                    ->maxSize(2048),
                Forms\Components\TextInput::make('video_url')
                    ->label('URL do Vídeo')
                    ->url()
                    ->maxLength(255),
                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadados')
                    ->keyLabel('Chave')
                    ->valueLabel('Valor'),
                Forms\Components\TextInput::make('order')
                    ->label('Ordem')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Chave')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo')
                    ->label('Ativo'),
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
            ->defaultSort('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPageSections::route('/'),
            'create' => Pages\CreateLandingPageSection::route('/create'),
            'edit' => Pages\EditLandingPageSection::route('/{record}/edit'),
        ];
    }
}
