<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Prêmios Disponíveis
            </x-slot>
            
            @if(count($this->getAvailableAwards()) > 0)
                <div class="space-y-2">
                    @foreach($this->getAvailableAwards() as $award)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $award['name'] }}</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $award['remaining'] }} restantes</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <x-filament::button 
                        wire:click="runDraw"
                        color="success"
                        size="lg"
                    >
                        Realizar Sorteio Semanal
                    </x-filament::button>
                </div>
            @else
                <div class="text-gray-500 dark:text-gray-400">
                    Nenhum prêmio disponível para sorteio.
                </div>
            @endif
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Histórico de Sorteios
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
