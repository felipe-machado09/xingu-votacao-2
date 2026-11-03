<div style="display: grid; gap: 0.5rem; width: 100%; max-width: 16rem;">
    <label for="report-year">Ano da edição</label>
    <x-filament::input.wrapper>
        <x-filament::input.select id="report-year" wire:model.live="year">
            @foreach(\App\Services\ReportYear::options() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </x-filament::input.select>
    </x-filament::input.wrapper>
</div>
