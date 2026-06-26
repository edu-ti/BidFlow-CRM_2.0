<?php

namespace Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

use Modules\Comercial\Filament\Resources\PropostaComercialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPropostaComercial extends EditRecord
{
    protected static string $resource = PropostaComercialResource::class;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('imprimir')
                ->label('Imprimir Proposta')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('propostas.imprimir', $this->record))
                ->openUrlInNewTab(),
            Actions\Action::make('voltar')
                ->label('Voltar')
                ->url(static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Salvar Proposta'),
            \Filament\Actions\Action::make('saveAndStay')
                ->label('Salvar e permanecer na mesma página')
                ->color('gray')
                ->action(function () {
                    $this->save(shouldRedirect: false);
                }),
            $this->getCancelFormAction(),
        ];
    }
}
