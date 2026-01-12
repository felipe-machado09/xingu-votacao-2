<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Select Category
            </x-slot>
            
            {{ $this->form }}
        </x-filament::section>

        @if($this->selectedCategoryId && $this->getCurrentWinner())
            <x-filament::section>
                <x-slot name="heading">
                    Current Winner 🏆
                </x-slot>
                
                <div class="text-lg">
                    <strong>{{ $this->getCurrentWinner()->company->legal_name }}</strong>
                    <div class="text-sm text-gray-500 mt-1">
                        Set on {{ $this->getCurrentWinner()->chosen_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </x-filament::section>
        @endif

        @if($this->selectedCategoryId)
            <x-filament::section>
                <x-slot name="heading">
                    Vote Results
                </x-slot>
                
                {{ $this->table }}
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
