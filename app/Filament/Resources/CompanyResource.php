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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
                Forms\Components\TextInput::make('trade_name')
                    ->label('Nome Fantasia')
                    ->formatStateUsing(fn ($state, ?Company $record) => $state ?: $record?->legal_name)
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('legal_name')
                    ->label('Razão Social')
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, \Filament\Schemas\Components\Utilities\Set $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Identificador da empresa. Os links públicos de votação ficam no cadastro de categorias.')
                    ->formatStateUsing(fn ($state) => $state ?? '')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(18)
                    ->mask('99.999.999/9999-99')
                    ->stripCharacters(['.', '/', '-'])
                    ->placeholder('00.000.000/0000-00')
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                    ->rules([new \App\Rules\ValidCNPJ()])
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('responsible_name')
                    ->label('Nome do Responsável')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('responsible_phone')
                    ->label('Telefone do Responsável')
                    ->tel()
                    ->mask('(99) 99999-9999')
                    ->placeholder('(00) 00000-0000')
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->tel()
                    ->required()
                    ->rules([new \App\Rules\ValidPhone])
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                    ->maxLength(20),
                Forms\Components\TextInput::make('instagram_url')
                    ->label('Link do Instagram')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->label('Categorias')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->required()
                    ->preload()
                    ->searchable(),
                Section::make('Imagens da Empresa')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logomarca')
                            ->required()
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
                Tables\Columns\TextColumn::make('trade_name')
                    ->label('Nome Fantasia')
                    ->getStateUsing(fn (Company $record) => $record->display_name)
                    ->searchable(['trade_name', 'legal_name'])
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy(DB::raw("COALESCE(NULLIF(trade_name, ''), legal_name)"), $direction))
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
                    ->counts(fn ($livewire) => ['votes' => fn ($query) => $query->forYear((int) ($livewire->getTableFilterState('year')['value'] ?? \App\Services\ReportYear::latest()))])
                    ->label('Votos')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')->label('Ano dos votos')
                    ->options(fn () => \App\Services\ReportYear::options())
                    ->default(fn () => \App\Services\ReportYear::latest())
                    ->query(fn ($query) => $query),
            ])
            ->headerActions([
                Actions\Action::make('exportExcel')->label('Extrair Excel')
                    ->action(fn ($livewire) => \App\Services\ExcelExport::download('empresas.xlsx',
                        ['Nome fantasia', 'CNPJ', 'WhatsApp', 'Instagram', 'Votos na edição'],
                        $livewire->getFilteredSortedTableQuery()->get()->map(fn (Company $company) => [
                            $company->display_name, $company->cnpj, $company->whatsapp_number, $company->instagram_url, $company->votes_count,
                        ]))),
            ])
            ->actions([
                Actions\Action::make('report')
                    ->label('Relatório')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn (Company $record): string => static::getUrl('report', ['record' => $record])),
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
            'report' => Pages\CompanyReport::route('/{record}/report'),
        ];
    }
}
