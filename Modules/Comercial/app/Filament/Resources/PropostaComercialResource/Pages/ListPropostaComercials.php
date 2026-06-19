<?php

namespace Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

use Modules\Comercial\Filament\Resources\PropostaComercialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropostaComercials extends ListRecords
{
    protected static string $resource = PropostaComercialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
