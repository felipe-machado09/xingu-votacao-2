<x-filament-panels::page>
    <div class="space-y-6">
        @if($this->selectedCategoryId)
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span>🏆</span>
                            <span>Top 10 - {{ $this->getSelectedCategory()?->name }}</span>
                        </div>
                        <button 
                            wire:click="closeResults"
                            class="text-gray-400 hover:text-gray-600 transition"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </x-slot>
                
                <div class="space-y-4">
                    @if($this->getCurrentWinner())
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border-2 border-yellow-400">
                            <div>
                                <div class="text-sm font-medium text-yellow-700 mb-1">Vencedor Atual</div>
                                <div class="text-lg font-bold text-yellow-900">
                                    {{ $this->getCurrentWinner()->company->legal_name }}
                                </div>
                                <div class="text-sm text-yellow-700 mt-1">
                                    Definido em {{ $this->getCurrentWinner()->chosen_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="text-4xl">🏆</div>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Ranking de Votos</h3>
                        <div class="space-y-2">
                            @forelse($this->getTopCompanies() as $index => $company)
                                <div class="flex items-center justify-between p-3 rounded-lg border {{ $index === 0 ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-orange-300 text-orange-900' : 'bg-gray-200 text-gray-600')) }} font-bold text-sm">
                                            {{ $index + 1 }}º
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium {{ $index === 0 ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $company['company_name'] }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $company['votes'] }} {{ $company['votes'] === 1 ? 'voto' : 'votos' }}
                                        </span>
                                        @if(!$this->getCurrentWinner() || $this->getCurrentWinner()->company_id !== $company['company_id'])
                                            <button 
                                                wire:click="setWinner({{ $company['company_id'] }})"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-yellow-500 text-white hover:bg-yellow-600 transition"
                                            >
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Definir Vencedor
                                            </button>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                                ✓ Vencedor
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    Nenhum voto registrado para esta categoria ainda.
                                </div>
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
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">Clique em "Ver Top 10" para visualizar o ranking de cada categoria e definir vencedores.</p>
            </div>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
