<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as ShieldRoleResource;

class RoleResource extends ShieldRoleResource
{
    protected static ?string $navigationLabel = 'Funções';

    protected static ?string $modelLabel = 'Função';

    protected static ?string $pluralModelLabel = 'Funções';

    protected static string | \UnitEnum | null $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 2;
}
