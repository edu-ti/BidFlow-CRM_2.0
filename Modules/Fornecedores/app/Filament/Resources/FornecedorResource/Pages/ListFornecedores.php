<?php

namespace Modules\Fornecedores\Filament\Resources\FornecedorResource\Pages;

use Modules\Fornecedores\Filament\Resources\FornecedorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFornecedores extends ListRecords
{
    protected static string $resource = FornecedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
