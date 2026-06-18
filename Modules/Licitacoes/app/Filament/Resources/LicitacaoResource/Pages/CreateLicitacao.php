<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\Pages;

use Modules\Licitacoes\Filament\Resources\LicitacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLicitacao extends CreateRecord
{
    protected static string $resource = LicitacaoResource::class;

    protected ?string $heading = 'Cadastrar Novo Pregão';
}
