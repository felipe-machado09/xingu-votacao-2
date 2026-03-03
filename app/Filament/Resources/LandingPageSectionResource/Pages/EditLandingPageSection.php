<?php

namespace App\Filament\Resources\LandingPageSectionResource\Pages;

use App\Filament\Resources\LandingPageSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandingPageSection extends EditRecord
{
    protected static string $resource = LandingPageSectionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->key === 'countdown' && isset($data['countdown_end_date'])) {
            $metadata = $data['metadata'] ?? $this->record->metadata ?? [];
            $metadata['end_date'] = $data['countdown_end_date'];
            $data['metadata'] = $metadata;
        }

        unset($data['countdown_end_date']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
