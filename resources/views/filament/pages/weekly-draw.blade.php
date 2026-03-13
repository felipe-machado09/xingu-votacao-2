<x-filament-panels::page>
    <div class="space-y-6">
        <div style="margin-bottom: 24px;">
            <x-filament::button
                tag="a"
                href="/sorteio"
                target="_blank"
                color="success"
                size="lg"
            >
                Realizar Sorteio Semanal
            </x-filament::button>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Histórico de Sorteios
            </x-slot>

            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
