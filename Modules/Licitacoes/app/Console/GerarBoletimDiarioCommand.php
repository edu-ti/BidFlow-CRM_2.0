<?php

namespace Modules\Licitacoes\Console;

use Illuminate\Console\Command;
use Modules\Licitacoes\Models\OportunidadeLicitacao;
use Modules\Licitacoes\Models\PerfilBusca;
use Modules\Licitacoes\Models\Boletim;
use Carbon\Carbon;

class GerarBoletimDiarioCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'licitacoes:boletim';

    /**
     * The console command description.
     */
    protected $description = 'Gera o boletim de licitacoes baseado nos perfis de busca ativos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando geracao do Boletim...");
        
        $perfis = PerfilBusca::where('ativo', true)->get();
        if ($perfis->isEmpty()) {
            $this->warn("Nenhum perfil de busca ativo encontrado. O boletim estara vazio se for gerado, abortando.");
            return;
        }

        // Pega as oportunidades cadastradas nas ultimas 24 horas (desde a ultima geracao)
        $dataCorte = now()->subHours(24);
        
        $oportunidadesCandidatas = OportunidadeLicitacao::where('created_at', '>=', $dataCorte)->get();
        
        $oportunidadesMatched = collect();

        foreach ($perfis as $perfil) {
            foreach ($oportunidadesCandidatas as $op) {
                // Se ja adicionou, pula
                if ($oportunidadesMatched->contains('id', $op->id)) {
                    continue;
                }

                $matchEstado = true;
                if (!empty($perfil->estados) && !in_array($op->estado, $perfil->estados)) {
                    $matchEstado = false;
                }

                $matchPalavra = true;
                if (!empty($perfil->palavras_chave)) {
                    $matchPalavra = false;
                    foreach ($perfil->palavras_chave as $palavra) {
                        if (stripos($op->objeto, $palavra) !== false) {
                            $matchPalavra = true;
                            break;
                        }
                    }
                }

                if ($matchEstado && $matchPalavra) {
                    $oportunidadesMatched->push($op);
                }
            }
        }

        $numeroEdicao = Boletim::max('numero_edicao') + 1;
        $titulo = "Boletim " . now()->isoFormat('D \d\e MMMM, HH:mm') . " - Edicao n " . $numeroEdicao;

        $boletim = Boletim::create([
            'titulo' => $titulo,
            'numero_edicao' => $numeroEdicao,
            'data_geracao' => now()
        ]);

        if ($oportunidadesMatched->count() > 0) {
            $boletim->oportunidades()->attach($oportunidadesMatched->pluck('id')->toArray());
            $this->info("Boletim gerado com sucesso! {$oportunidadesMatched->count()} oportunidades vinculadas.");
        } else {
            $this->info("Boletim gerado, porem nenhuma oportunidade compativel foi encontrada hoje.");
        }
    }
}
