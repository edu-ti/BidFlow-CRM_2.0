<?php

namespace Modules\Consignado\Filament\Resources\ContratoResource\Pages;

use Modules\Consignado\Filament\Resources\ContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContrato extends EditRecord
{
    protected static string $resource = ContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
