<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\Pages;

use Modules\Licitacoes\Filament\Resources\LicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLicitacoes extends ListRecords
{
    protected static string $resource = LicitacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Novo Pregão'),
        ];
    }
}
