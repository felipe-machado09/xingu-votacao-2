<x-filament-panels::page>
    <x-report-year />
    <div style="display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem;">
        @if($this->selectedCategoryId)
            <x-filament::section>
                <x-slot name="heading">
                    Top 10 - {{ $this->getSelectedCategory()?->name }}
                </x-slot>
                <x-slot name="afterHeader">
                    <x-filament::button color="gray" size="sm" icon="heroicon-m-x-mark"
                        aria-label="Fechar ranking da categoria" wire:click="closeResults">
                        Fechar ranking
                    </x-filament::button>
                </x-slot>

                <div style="display: grid; gap: 1.5rem;">
                    @if($this->getCurrentWinner())
                        <x-filament::section heading="Vencedor atual" compact>
                            <p style="font-weight: 600;">{{ $this->getCurrentWinner()->company->display_name }}</p>
                            <p class="fi-section-header-description">
                                Definido em {{ $this->getCurrentWinner()->chosen_at->format('d/m/Y H:i') }}
                            </p>
                        </x-filament::section>
                    @endif

                    <div>
                        <h3 style="font-weight: 600; margin-bottom: 1rem;">Ranking de votos</h3>
                        <div style="display: grid; gap: 0.75rem;">
                            @forelse($this->getTopCompanies() as $index => $company)
                                <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid rgba(128, 128, 128, 0.25);">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0; flex: 1 1 12rem;">
                                        <x-filament::badge :color="$index === 0 ? 'warning' : 'gray'">
                                            {{ $index + 1 }}º
                                        </x-filament::badge>
                                        <span style="font-weight: 500; overflow-wrap: anywhere;">{{ $company['company_name'] }}</span>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;">
                                        <x-filament::badge color="success">
                                            {{ $company['votes'] }} {{ $company['votes'] === 1 ? 'voto' : 'votos' }}
                                        </x-filament::badge>
                                        @if(!$this->getCurrentWinner() || $this->getCurrentWinner()->company_id !== $company['company_id'])
                                            <x-filament::button
                                                color="warning"
                                                size="sm"
                                                icon="heroicon-m-check"
                                                wire:click="setWinner({{ $company['company_id'] }})"
                                                wire:confirm="Definir esta empresa como vencedora da categoria nesta edição?"
                                            >
                                                Definir Vencedor
                                            </x-filament::button>
                                        @else
                                            <x-filament::badge color="warning">
                                                ✓ Vencedor
                                            </x-filament::badge>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="fi-section-header-description">
                                    Nenhum voto registrado para esta categoria ainda.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        <x-filament::section>
            <x-slot name="heading">
                Categorias
            </x-slot>

            <div style="margin-bottom: 1rem;">
                <p class="fi-section-header-description">Clique em "Ver Top 10" para visualizar o ranking de cada categoria e definir vencedores.</p>
            </div>

            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
