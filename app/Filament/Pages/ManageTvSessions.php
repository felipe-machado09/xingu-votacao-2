<?php

namespace App\Filament\Pages;

use App\Models\TvSession;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageTvSessions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tv';

    protected static ?string $navigationLabel = 'Gerenciar TVs';

    protected static ?string $title = 'Gerenciar TVs (BI)';

    protected static string | \UnitEnum | null $navigationGroup = 'Relatórios';

    protected string $view = 'filament.pages.manage-tv-sessions';

    protected static ?int $navigationSort = 3;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('activateTv')
                ->label('Liberar TV')
                ->icon('heroicon-o-tv')
                ->color('primary')
                ->modalHeading('Liberar TV')
                ->modalDescription('Digite o código de 6 dígitos exibido na tela da TV para liberar o painel.')
                ->modalSubmitActionLabel('Liberar')
                ->modalIcon('heroicon-o-tv')
                ->form([
                    TextInput::make('activationCode')
                        ->label('Código da TV')
                        ->placeholder('000000')
                        ->required()
                        ->minLength(6)
                        ->maxLength(6)
                        ->numeric(),
                    TextInput::make('tvName')
                        ->label('Nome da TV (opcional)')
                        ->placeholder('Ex: TV Recepção'),
                ])
                ->action(function (array $data) {
                    $code = trim($data['activationCode']);

                    $session = TvSession::where('code', $code)
                        ->where('is_active', false)
                        ->first();

                    if (!$session) {
                        Notification::make()
                            ->title('Código não encontrado')
                            ->body('Nenhuma TV pendente com esse código. Verifique o código exibido na tela da TV.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $session->activate(
                        userId: Filament::auth()->id(),
                        name: $data['tvName'] ?: 'TV #' . $session->id
                    );

                    Notification::make()
                        ->title('TV liberada!')
                        ->body("A TV \"{$session->name}\" (código {$session->code}) está exibindo o painel agora.")
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * Tabela de sessões de TV
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(TvSession::query()->latest())
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->size('lg')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nome da TV')
                    ->searchable()
                    ->default('—'),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('activatedByUser.name')
                    ->label('Liberado por')
                    ->placeholder('—'),

                TextColumn::make('activated_at')
                    ->label('Ativado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                \Filament\Actions\Action::make('deactivate')
                    ->label('Desativar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (TvSession $record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Desativar TV')
                    ->modalDescription('A TV voltará a mostrar a tela de código. Deseja continuar?')
                    ->action(function (TvSession $record) {
                        $record->deactivate();
                        Notification::make()
                            ->title('TV desativada')
                            ->body("A TV \"{$record->name}\" foi desativada.")
                            ->warning()
                            ->send();
                    }),

                \Filament\Actions\Action::make('activate')
                    ->label('Reativar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (TvSession $record) => !$record->is_active)
                    ->requiresConfirmation()
                    ->action(function (TvSession $record) {
                        $record->activate(Filament::auth()->id());
                        Notification::make()
                            ->title('TV reativada')
                            ->body("A TV \"{$record->name}\" foi reativada.")
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('delete')
                    ->label('Excluir')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (TvSession $record) => $record->delete()),
            ])
            ->poll('5s')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Nenhuma TV registrada')
            ->emptyStateDescription('Quando uma TV acessar /bi, um código aparecerá na tela. Insira o código acima para liberar.');
    }
}
