<?php

namespace Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

use Modules\Comercial\Filament\Resources\PropostaComercialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropostaComercial extends EditRecord
{
    protected static string $resource = PropostaComercialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
