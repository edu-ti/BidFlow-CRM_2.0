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
            Actions\Action::make('gerar_proposta')
                ->label('Gerar Proposta PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function ($record) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('licitacoes::pdf.proposta', ['licitacao' => $record]);
                    $filename = 'proposta-' . str_replace(['/', '\\'], '-', $record->numero_edital) . '.pdf';
                    return response()->streamDownload(fn () => print($pdf->output()), $filename);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
