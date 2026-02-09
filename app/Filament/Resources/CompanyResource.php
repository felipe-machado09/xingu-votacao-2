<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Empresas';

    protected static ?string $modelLabel = 'Empresa';

    protected static ?string $pluralModelLabel = 'Empresas';

    protected static string | \UnitEnum | null $navigationGroup = 'Votação';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('legal_name')
                    ->label('Razão Social')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Usado na URL da página da empresa (ex: /empresa/halvorson-llc)')
                    ->formatStateUsing(fn ($state) => $state ?? '')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^\d{14}$/'])
                    ->maxLength(18)
                    ->mask('99.999.999/9999-99')
                    ->placeholder('00.000.000/0000-00')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('responsible_name')
                    ->label('Nome do Responsável')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('responsible_phone')
                    ->label('Telefone do Responsável')
                    ->tel()
                    ->required()
                    ->mask('(99) 99999-9999')
                    ->placeholder('(00) 00000-0000')
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->label('Categorias')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Section::make('Imagens da Empresa')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('company-logos')
                            ->disk('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Logo da empresa (obrigatório)'),
                        Forms\Components\FileUpload::make('main_image_path')
                            ->label('Imagem Principal')
                            ->image()
                            ->directory('company-images')
                            ->disk('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Imagem principal da empresa (opcional)'),
                        Forms\Components\FileUpload::make('facade_image_path')
                            ->label('Imagem da Fachada')
                            ->image()
                            ->directory('company-images')
                            ->disk('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Foto da fachada da empresa (opcional)'),
                        Forms\Components\FileUpload::make('team_image_path')
                            ->label('Imagem da Equipe')
                            ->image()
                            ->directory('company-images')
                            ->disk('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Foto da equipe da empresa (opcional)'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Razão Social')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('cnpj')
                    ->label('CNPJ')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string =>
                        preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $state)
                    )
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('responsible_name')
                    ->label('Responsável')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categorias')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('votes_count')
                    ->counts('votes')
                    ->label('Votos')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
