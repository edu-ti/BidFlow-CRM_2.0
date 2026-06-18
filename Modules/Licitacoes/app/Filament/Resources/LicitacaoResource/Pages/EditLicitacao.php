<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\Pages;

use Modules\Licitacoes\Filament\Resources\LicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLicitacao extends EditRecord
{
    protected static string $resource = LicitacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
