<?php

namespace Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

use Modules\Comercial\Filament\Resources\PropostaComercialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePropostaComercial extends CreateRecord
{
    protected static string $resource = PropostaComercialResource::class;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('voltar')
                ->label('Voltar')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Criar Proposta'),
            Actions\Action::make('createAndEdit')
                ->label('Criar e permanecer na mesma página')
                ->color('gray')
                ->action(function () {
                    $this->create();
                    return redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),
            $this->getCancelFormAction(),
        ];
    }
}
