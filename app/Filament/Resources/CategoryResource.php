<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias';

    protected static ?string $navigationGroup = 'Votação';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    )
                    ->maxLength(255),
                Forms\Components\TextInput::make('category_group')
                    ->label('Grupo de Categoria')
                    ->placeholder('Ex: Alimentação e Bebidas, Comércio e Varejo, etc.')
                    ->maxLength(255)
                    ->helperText('Agrupa categorias relacionadas para facilitar a seleção no cadastro de empresas'),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descri\u00e7\u00e3o')
                    ->rows(3),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
                Forms\Components\DateTimePicker::make('voting_starts_at')
                    ->label('In\u00edcio da Vota\u00e7\u00e3o')
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false),
                Forms\Components\DateTimePicker::make('voting_ends_at')
                    ->label('Fim da Vota\u00e7\u00e3o')
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category_group')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('companies_count')
                    ->counts('companies')
                    ->label('Empresas')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('votes_count')
                    ->counts('votes')
                    ->label('Votos')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('voting_starts_at')
                    ->label('In\u00edcio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('voting_ends_at')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('category_group')
                    ->label('Grupo de Categoria')
                    ->options(function () {
                        return Category::distinct()
                            ->whereNotNull('category_group')
                            ->pluck('category_group', 'category_group')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
