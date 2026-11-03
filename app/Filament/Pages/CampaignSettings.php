<?php

namespace App\Filament\Pages;

use App\Models\LandingPageSection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $title = 'Configurações da Edição';

    protected static string|\UnitEnum|null $navigationGroup = 'Conteúdo';

    protected string $view = 'filament.pages.campaign-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('Update:LandingPageSection') ?? false;
    }

    public function mount(): void
    {
        $campaign = LandingPageSection::getByKey('campaign');
        $this->form->fill([
            'year' => LandingPageSection::campaignYear(),
            'logo' => $campaign?->image ?? LandingPageSection::getByKey('logo')?->image ?? LandingPageSection::getByKey('hero')?->image,
            'end_date' => LandingPageSection::votingEndsAt(),
            'regulation' => $campaign?->document_path ?? LandingPageSection::getByKey('regulation')?->document_path,
            'company_registration_enabled' => LandingPageSection::campaignOption('company_registration_enabled'),
            'randomize_companies' => LandingPageSection::campaignOption('randomize_companies'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->columns(2)->schema([
            Section::make('Identidade da edição')
                ->description('Ano, logomarca e regulamento exibidos nas páginas da campanha.')
                ->schema([
                    TextInput::make('year')->label('Ano da edição')->integer()->required()->minValue(2000)->maxValue(2100),
                    FileUpload::make('logo')->label('Logomarca da edição')->image()->disk('public')->directory('campaign-logos')->maxSize(2048),
                    FileUpload::make('regulation')->label('Regulamento (PDF)')->disk('public')->directory('regulations')
                        ->acceptedFileTypes(['application/pdf'])->maxSize(10240),
                ]),
            Section::make('Votação e cadastro')
                ->description('Controle o prazo e a participação nesta edição.')
                ->schema([
                    DateTimePicker::make('end_date')->label('Encerramento da votação')->seconds(false)
                        ->native(false)->displayFormat('d/m/Y H:i')
                        ->helperText('A votação será bloqueada automaticamente nesta data e hora. Sem data, valem os prazos das categorias.'),
                    Toggle::make('company_registration_enabled')->label('Permitir cadastro público de empresas'),
                    Toggle::make('randomize_companies')->label('Embaralhar empresas ao abrir a categoria'),
                ]),
        ]);
    }

    public function save(): void
    {
        abort_unless(static::canAccess(), 403);
        $data = $this->form->getState();
        LandingPageSection::updateOrCreate(['key' => 'campaign'], [
            'title' => 'Configurações da edição',
            'is_active' => true,
            'image' => $data['logo'],
            'document_path' => $data['regulation'],
            'metadata' => [
                'year' => (int) $data['year'],
                'end_date' => $data['end_date'],
                'company_registration_enabled' => $data['company_registration_enabled'],
                'randomize_companies' => $data['randomize_companies'],
            ],
        ]);
        Notification::make()->title('Configurações salvas')->success()->send();
    }
}
