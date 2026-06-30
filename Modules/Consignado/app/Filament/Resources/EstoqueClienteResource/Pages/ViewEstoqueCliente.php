<?php

namespace Modules\Consignado\Filament\Resources\EstoqueClienteResource\Pages;

use Modules\Consignado\Filament\Resources\EstoqueClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEstoqueCliente extends ViewRecord
{
    protected static string $resource = EstoqueClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('voltar')
                ->label('Voltar')
                ->url(EstoqueClienteResource::getUrl('index'))
                ->color('gray')
                ->button(),
        ];
    }
}
