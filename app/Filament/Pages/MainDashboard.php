<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class MainDashboard extends BaseDashboard
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -100;
    
    protected string $view = 'filament.pages.main-dashboard';

    public function getHeading(): string | Htmlable
    {
        return '';
    }

    protected function getViewData(): array
    {
        $mesesNomes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $anoAtual = date('Y');

        // === DADOS COMERCIAIS (OPORTUNIDADES) ===
        $oportunidades = \Modules\Comercial\Models\Oportunidade::all();
        $totalNegocios = $oportunidades->count();
        $totalClientes = $oportunidades->whereNotNull('fornecedor_id')->unique('fornecedor_id')->count();
        
        $oportunidadesGanhas = $oportunidades->filter(function ($op) {
            $status = strtolower($op->status);
            return str_contains($status, 'ganho') || str_contains($status, 'aprovad') || str_contains($status, 'fechad');
        });
        
        $oportunidadesPerdidas = $oportunidades->filter(function ($op) {
            $status = strtolower($op->status);
            return str_contains($status, 'perdid') || str_contains($status, 'recusad') || str_contains($status, 'cancelad');
        });

        $negociosGanhos = $oportunidadesGanhas->count();
        $negociosPerdidos = $oportunidadesPerdidas->count();
        $receitaTotalComercial = $oportunidadesGanhas->sum('valor_estimado');
        $taxaConversaoComercial = $totalNegocios > 0 ? round(($negociosGanhos / $totalNegocios) * 100, 1) : 0;
        
        $taxaSucessoGanho = $totalNegocios > 0 ? round(($negociosGanhos / $totalNegocios) * 100, 1) : 0;
        $taxaSucessoPerdida = $totalNegocios > 0 ? round(($negociosPerdidos / $totalNegocios) * 100, 1) : 0;
        $metaVendas = 200000;
        $gaugeMetaComercial = min(($receitaTotalComercial / $metaVendas) * 100, 100);

        // Agrupamento Mensal de Receita Comercial
        $receitaMensalComercial = array_fill(0, 12, 0);
        foreach ($oportunidadesGanhas as $op) {
            if ($op->created_at && $op->created_at->format('Y') == $anoAtual) {
                $mesIndex = (int)$op->created_at->format('m') - 1;
                $receitaMensalComercial[$mesIndex] += $op->valor_estimado;
            }
        }

        // Em Andamento
        $negociosEmAndamento = $totalNegocios - $negociosGanhos - $negociosPerdidos;

        // Dados para o Gráfico de Vendedores
        $vendedoresData = $oportunidades->groupBy('user_id')->map(function ($grupo) {
            $user = $grupo->first()->user;
            return [
                'nome' => $user ? explode(' ', $user->name)[0] : 'Desconhecido',
                'receita' => $grupo->sum('valor_estimado')
            ];
        })->values()->take(8);

        $comercialData = [
            'conversionRate' => $taxaConversaoComercial . '%',
            'revenue' => number_format($receitaTotalComercial, 2, ',', '.'),
            'salesAtual' => 'R$ ' . number_format($receitaTotalComercial, 2, ',', '.'),
            'gaugeMeta' => round($gaugeMetaComercial, 1),
            'taxaSucesso' => [
                'ganhoPct' => $taxaSucessoGanho . '%',
                'ganhoQtd' => $negociosGanhos,
                'ganhoLabel' => $totalClientes . ' Clientes',
                'perdidoPct' => $taxaSucessoPerdida . '%',
                'perdidoQtd' => $negociosPerdidos,
                'perdidoLabel' => $totalNegocios . ' Propostas'
            ],
            'chartReceitaMensal' => $receitaMensalComercial,
            'stats' => [
                [ 'label' => 'Total Negócios', 'value' => $totalNegocios, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>' ],
                [ 'label' => 'Total Clientes', 'value' => $totalClientes, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' ],
                [ 'label' => 'Receita Total', 'value' => 'R$ ' . number_format($receitaTotalComercial, 2, ',', '.'), 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' ],
                [ 'label' => 'Negócios Ganhos', 'value' => $negociosGanhos, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>' ]
            ],
            'chartCategories' => $vendedoresData->pluck('nome')->toArray(),
            'chartValues' => $vendedoresData->pluck('receita')->toArray(),
            'chartStatus' => [
                'ganho' => $negociosGanhos,
                'andamento' => $negociosEmAndamento,
                'perdido' => $negociosPerdidos
            ]
        ];

        // === DADOS LICITAÇÕES ===
        $licitacoes = \Modules\Licitacoes\Models\Licitacao::with('itens')->get();
        $totalPropostas = $licitacoes->count();
        $fornecedoresAtivos = \Modules\Fornecedores\Models\Fornecedor::count();

        $licitacoesGanhas = $licitacoes->filter(function ($lic) {
            $status = strtolower($lic->status);
            return str_contains($status, 'ganho') || str_contains($status, 'aprovad') || str_contains($status, 'fechad');
        });
        
        $licitacoesPerdidas = $licitacoes->filter(function ($lic) {
            $status = strtolower($lic->status);
            return str_contains($status, 'perdid') || str_contains($status, 'recusad') || str_contains($status, 'cancelad');
        });

        $totalLicitacoesGanhas = $licitacoesGanhas->count();
        $totalLicitacoesPerdidas = $licitacoesPerdidas->count();
        $receitaTotalLicitacoes = $licitacoesGanhas->pluck('itens')->flatten()->sum('valor_total');
        $taxaConversaoLicitacoes = $totalPropostas > 0 ? round(($totalLicitacoesGanhas / $totalPropostas) * 100, 1) : 0;
        
        $taxaSucessoLicGanho = $totalPropostas > 0 ? round(($totalLicitacoesGanhas / $totalPropostas) * 100, 1) : 0;
        $taxaSucessoLicPerdida = $totalPropostas > 0 ? round(($totalLicitacoesPerdidas / $totalPropostas) * 100, 1) : 0;
        $gaugeMetaLicitacao = min(($receitaTotalLicitacoes / $metaVendas) * 100, 100);

        // Agrupamento Mensal de Receita Licitações
        $receitaMensalLicitacao = array_fill(0, 12, 0);
        foreach ($licitacoesGanhas as $lic) {
            if ($lic->created_at && $lic->created_at->format('Y') == $anoAtual) {
                $mesIndex = (int)$lic->created_at->format('m') - 1;
                $receitaMensalLicitacao[$mesIndex] += $lic->itens->sum('valor_total');
            }
        }

        $licitacoesEmAndamento = $totalPropostas - $totalLicitacoesGanhas - $totalLicitacoesPerdidas;

        // Dados para o Gráfico de Fornecedores/Órgãos
        $orgaosData = $licitacoes->groupBy('orgao_razao_social')->map(function ($grupo) {
            return [
                'nome' => $grupo->first()->orgao_razao_social ?? 'Não Informado',
                'receita' => $grupo->pluck('itens')->flatten()->sum('valor_total')
            ];
        })->values()->take(8);

        $licitacaoData = [
            'conversionRate' => $taxaConversaoLicitacoes . '%',
            'revenue' => number_format($receitaTotalLicitacoes, 2, ',', '.'),
            'salesAtual' => 'R$ ' . number_format($receitaTotalLicitacoes, 2, ',', '.'),
            'gaugeMeta' => round($gaugeMetaLicitacao, 1),
            'taxaSucesso' => [
                'ganhoPct' => $taxaSucessoLicGanho . '%',
                'ganhoQtd' => $totalLicitacoesGanhas,
                'ganhoLabel' => 'Licitações',
                'perdidoPct' => $taxaSucessoLicPerdida . '%',
                'perdidoQtd' => $totalLicitacoesPerdidas,
                'perdidoLabel' => $totalPropostas . ' Total'
            ],
            'chartReceitaMensal' => $receitaMensalLicitacao,
            'stats' => [
                [ 'label' => 'Total Propostas', 'value' => $totalPropostas, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' ],
                [ 'label' => 'Fornecedores Ativos', 'value' => $fornecedoresAtivos, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' ],
                [ 'label' => 'Total Faturado', 'value' => 'R$ ' . number_format($receitaTotalLicitacoes, 2, ',', '.'), 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' ],
                [ 'label' => 'Licitações Ganhas', 'value' => $totalLicitacoesGanhas, 'growth' => '0%', 'icon' => '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>' ]
            ],
            'chartCategories' => $orgaosData->pluck('nome')->toArray(),
            'chartValues' => $orgaosData->pluck('receita')->toArray(),
            'chartStatus' => [
                'ganho' => $totalLicitacoesGanhas,
                'andamento' => $licitacoesEmAndamento,
                'perdido' => $totalLicitacoesPerdidas
            ]
        ];
        
        $ultimosNegocios = \Modules\Comercial\Models\Oportunidade::with(['user', 'fornecedor'])->orderBy('created_at', 'desc')->take(5)->get();
        $ultimasLicitacoes = \Modules\Licitacoes\Models\Licitacao::orderBy('created_at', 'desc')->take(5)->get();

        return [
            'comercialData' => $comercialData,
            'licitacaoData' => $licitacaoData,
            'ultimosNegocios' => $ultimosNegocios,
            'ultimasLicitacoes' => $ultimasLicitacoes,
        ];
    }
}
