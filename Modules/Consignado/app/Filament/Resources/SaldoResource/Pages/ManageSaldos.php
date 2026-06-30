<?php

namespace Modules\Consignado\Filament\Resources\SaldoResource\Pages;

use Modules\Consignado\Filament\Resources\SaldoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSaldos extends ManageRecords
{
    protected static string $resource = SaldoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
