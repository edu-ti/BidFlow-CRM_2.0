<?php

namespace Modules\Comercial\Filament\Resources\TarefaAgendaResource\Pages;

use Modules\Comercial\Filament\Resources\TarefaAgendaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTarefaAgendas extends ListRecords
{
    protected static string $resource = TarefaAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
