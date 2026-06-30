<?php

namespace Modules\Consignado\Filament\Resources\MovimentacaoResource\Pages;

use Modules\Consignado\Filament\Resources\MovimentacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMovimentacaos extends ManageRecords
{
    protected static string $resource = MovimentacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nova Movimentação'),
        ];
    }
}
