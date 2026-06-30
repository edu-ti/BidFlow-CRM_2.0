<?php

namespace Modules\Licitacoes\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Licitacoes\Models\OportunidadeLicitacao;
use Carbon\Carbon;

class ImportarOportunidadesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'licitacoes:importar';

    /**
     * The console command description.
     */
    protected $description = 'Importa licitacoes do PNCP e Compras.gov.br para a tabela de oportunidades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando captura de licitacoes...");
        
        $dataDeHoje = now()->format('Y-m-d');
        
        $this->extrairPncp($dataDeHoje);
        $this->extrairComprasGov($dataDeHoje);
        
        $this->info("Processo finalizado com sucesso!");
    }

    private function extrairPncp($dataConsulta)
    {
        $this->info("Buscando no PNCP...");
        
        $dataFormatada = Carbon::parse($dataConsulta)->format('Ymd');
        $url = "https://pncp.gov.br/api/consulta/v1/contratacoes/publicacao?dataInicial={$dataFormatada}&dataFinal={$dataFormatada}";
        
        try {
            $response = Http::withoutVerifying()
                ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) GestaoLicitacaoBot/1.0')
                ->get($url);
                
            if ($response->successful()) {
                $dados = $response->json();
                
                if (isset($dados['data']) && is_array($dados['data'])) {
                    $processados = 0;
                    foreach ($dados['data'] as $item) {
                        $orgao = $item['orgaoEntidade']['razaoSocial'] ?? 'Orgao nao informado';
                        $objeto = $item['objetoCompra'] ?? '';
                        $edital = $item['numeroCompra'] ?? '';
                        $estado = $item['unidadeOrgao']['ufSigla'] ?? '';
                        $modalidade = $item['modalidadeNome'] ?? '';
                        $link = $item['linkSistemaOrigem'] ?? '';
                        $dataAbertura = isset($item['dataAberturaProposta']) ? Carbon::parse($item['dataAberturaProposta'])->format('Y-m-d H:i:s') : null;
                        
                        $this->salvarNoBanco('PNCP', $orgao, $objeto, $edital, $estado, $modalidade, $dataAbertura, $link);
                        $processados++;
                    }
                    $this->info("PNCP: {$processados} registros processados.");
                }
            }
        } catch (\Exception $e) {
            $this->error("Erro ao buscar no PNCP: " . $e->getMessage());
        }
    }

    private function extrairComprasGov($dataConsulta)
    {
        $this->info("Buscando no Compras.gov.br...");
        
        $url = "https://dadosabertos.compras.gov.br/modulo-editais/1_licitacoes?data_publicacao={$dataConsulta}";
        
        try {
            $response = Http::withoutVerifying()->get($url);
                
            if ($response->successful()) {
                $dados = $response->json();
                
                if (isset($dados['resultado']) && is_array($dados['resultado'])) {
                    $processados = 0;
                    foreach ($dados['resultado'] as $item) {
                        $orgao = $item['nome_uasg'] ?? 'Orgao Federal';
                        $objeto = $item['objeto'] ?? '';
                        $edital = $item['numero_licitacao'] ?? '';
                        $estado = $item['uf_uasg'] ?? '';
                        $modalidade = 'Pregao/Concorrencia (Federal)';
                        $link = "https://comprasnet.gov.br/";
                        $dataAbertura = isset($item['data_abertura']) ? Carbon::parse($item['data_abertura'])->format('Y-m-d H:i:s') : null;
                        
                        $this->salvarNoBanco('Compras.gov', $orgao, $objeto, $edital, $estado, $modalidade, $dataAbertura, $link);
                        $processados++;
                    }
                    $this->info("Compras.gov: {$processados} registros processados.");
                }
            }
        } catch (\Exception $e) {
            $this->error("Erro ao buscar no Compras.gov: " . $e->getMessage());
        }
    }

    private function salvarNoBanco($origem, $orgao, $objeto, $edital, $estado, $modalidade, $dataAbertura, $link)
    {
        $existe = OportunidadeLicitacao::where('orgao', mb_substr($orgao, 0, 250))
            ->where('edital', mb_substr($edital, 0, 250))
            ->where('portal_origem', $origem)
            ->exists();
            
        if (!$existe) {
            OportunidadeLicitacao::create([
                'orgao' => mb_substr($orgao, 0, 250),
                'objeto' => $objeto,
                'edital' => mb_substr($edital, 0, 250),
                'estado' => mb_substr($estado, 0, 2),
                'modalidade' => mb_substr($modalidade, 0, 250),
                'data_abertura' => $dataAbertura,
                'link_detalhes' => mb_substr($link, 0, 1000),
                'portal_origem' => $origem,
                'status_badge' => 'NOVA',
                'data_publicacao' => now(),
            ]);
        }
    }
}
