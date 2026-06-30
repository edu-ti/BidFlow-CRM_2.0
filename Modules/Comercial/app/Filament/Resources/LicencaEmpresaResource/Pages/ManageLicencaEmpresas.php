<?php

namespace Modules\Comercial\Filament\Resources\LicencaEmpresaResource\Pages;

use Modules\Comercial\Filament\Resources\LicencaEmpresaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLicencaEmpresas extends ManageRecords
{
    protected static string $resource = LicencaEmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Nova Licença/Certidão'),
        ];
    }
}
