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
            \Filament\Actions\Action::make('kanban')
                ->label('Ver Kanban')
                ->icon('heroicon-m-view-columns')
                ->color('gray')
                ->url(route('filament.admin.pages.funil-vendas-board')),
            Actions\CreateAction::make(),
        ];
    }
}
