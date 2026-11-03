<x-filament-panels::page>
    <p>Configure a edição aqui. Os textos e as imagens das demais seções continuam disponíveis em Seções da Landing.</p>
    <form wire:submit="save">
        {{ $this->form }}
        <div style="margin-top: 1.5rem;">
            <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="save">
                Salvar configurações
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
