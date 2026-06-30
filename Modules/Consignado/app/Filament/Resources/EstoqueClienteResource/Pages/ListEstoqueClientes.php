<?php

namespace Modules\Consignado\Filament\Resources\EstoqueClienteResource\Pages;

use Modules\Consignado\Filament\Resources\EstoqueClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstoqueClientes extends ListRecords
{
    protected static string $resource = EstoqueClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
