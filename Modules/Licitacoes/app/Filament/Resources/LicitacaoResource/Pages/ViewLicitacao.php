<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\Pages;

use Modules\Licitacoes\Filament\Resources\LicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLicitacao extends ViewRecord
{
    protected static string $resource = LicitacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
