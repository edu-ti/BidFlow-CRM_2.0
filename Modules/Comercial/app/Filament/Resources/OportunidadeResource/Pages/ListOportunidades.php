<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

use Modules\Comercial\Filament\Resources\OportunidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOportunidades extends ListRecords
{
    protected static string $resource = OportunidadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
