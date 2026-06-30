<?php

namespace Modules\Consignado\Filament\Resources\ContratoResource\Pages;

use Modules\Consignado\Filament\Resources\ContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContratos extends ListRecords
{
    protected static string $resource = ContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
