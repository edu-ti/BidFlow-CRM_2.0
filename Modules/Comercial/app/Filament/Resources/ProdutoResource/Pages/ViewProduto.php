<?php

namespace Modules\Comercial\Filament\Resources\ProdutoResource\Pages;

use Modules\Comercial\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduto extends ViewRecord
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
