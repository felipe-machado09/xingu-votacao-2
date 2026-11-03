<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias';

    protected static string | \UnitEnum | null $navigationGroup = 'Votação';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
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
                    ->label('Descrição')
                    ->rows(3),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
                Forms\Components\DateTimePicker::make('voting_starts_at')
                    ->label('Início da Votação')
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false),
                Forms\Components\DateTimePicker::make('voting_ends_at')
                    ->label('Fim da Votação')
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('voting_url')
                    ->label('Link de votação')
                    ->state(fn (Category $record) => route('vote.category', $record))
                    ->copyable()
                    ->url(fn (Category $record) => route('vote.category', $record))
                    ->openUrlInNewTab(),
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
                    ->counts(fn ($livewire) => ['votes' => fn ($query) => $query->forYear((int) ($livewire->getTableFilterState('year')['value'] ?? \App\Services\ReportYear::latest()))])
                    ->label('Votos')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('voting_starts_at')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('voting_ends_at')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')->label('Ano dos votos')
                    ->options(fn () => \App\Services\ReportYear::options())
                    ->default(fn () => \App\Services\ReportYear::latest())
                    ->query(fn ($query) => $query),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
                Tables\Filters\SelectFilter::make('category_group')
                    ->label('Grupo')
                    ->label('Grupo de Categoria')
                    ->options(function () {
                        return Category::distinct()
                            ->whereNotNull('category_group')
                            ->pluck('category_group', 'category_group')
                            ->toArray();
                    }),
            ])
            ->headerActions([
                Actions\Action::make('exportExcel')->label('Extrair Excel')
                    ->action(fn ($livewire) => \App\Services\ExcelExport::download('categorias.xlsx',
                        ['Categoria', 'Link de votação', 'Votos na edição'],
                        $livewire->getFilteredSortedTableQuery()->get()->map(fn (Category $category) => [
                            $category->name, route('vote.category', $category), $category->votes_count,
                        ]))),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
