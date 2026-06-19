<?php

namespace Modules\Comercial\Filament\Resources\TarefaAgendaResource\Pages;

use Modules\Comercial\Filament\Resources\TarefaAgendaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTarefaAgenda extends EditRecord
{
    protected static string $resource = TarefaAgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
