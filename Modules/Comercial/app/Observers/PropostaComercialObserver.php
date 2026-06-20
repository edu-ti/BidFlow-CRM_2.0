<?php

namespace Modules\Comercial\Observers;

use Modules\Comercial\Models\PropostaComercial;

class PropostaComercialObserver
{
    public function saved(PropostaComercial $proposta)
    {
        // Update Oportunidade value
        if ($proposta->oportunidade) {
            $proposta->oportunidade->update([
                'valor_estimado' => $proposta->oportunidade->propostas()->sum('valor_total')
            ]);
        }

        // Reverse-Sync do Funil
        if ($proposta->wasChanged('status')) {
            $oportunidade = $proposta->oportunidade;
            if ($oportunidade) {
                if ($proposta->status === 'Enviada' && $oportunidade->status !== 'Negociação' && $oportunidade->status !== 'Fechado/Ganho') {
                    $oportunidade->update(['status' => 'Negociação']);
                }

                if ($proposta->status === 'Aprovada') {
                    $oportunidade->update(['status' => 'Fechado/Ganho']);
                    
                    // Handoff Financeiro
                    $this->triggerFinancialHandoff($proposta);
                }
            }
        }
    }

    public function deleted(PropostaComercial $proposta)
    {
        if ($proposta->oportunidade) {
            $proposta->oportunidade->update([
                'valor_estimado' => $proposta->oportunidade->propostas()->sum('valor_total')
            ]);
        }
    }

    protected function triggerFinancialHandoff(PropostaComercial $proposta)
    {
        // Emitir log/evento para handoff financeiro conforme respondido pelo usuário
        \Illuminate\Support\Facades\Log::info("Handoff financeiro acionado para Proposta ID: {$proposta->id} da Oportunidade ID: {$proposta->oportunidade_id}");
        // TODO: Mapear futuro evento do Módulo Financeiro
        // event(new \Modules\Comercial\Events\PropostaAprovada($proposta));
    }
}
