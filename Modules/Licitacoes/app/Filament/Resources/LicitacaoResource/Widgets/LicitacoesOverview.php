<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Licitacoes\Models\Licitacao;

class LicitacoesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Pregões', Licitacao::count())
                ->description('Gerenciados pela empresa')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),
                
            Stat::make('Em Análise', Licitacao::where('status', 'Em análise')->count())
                ->description('Aguardando envio de proposta')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color('warning'),
                
            Stat::make('Adjudicados/Homologados', Licitacao::whereIn('status', ['Adjudicado', 'Homologado'])->count())
                ->description('Licitações ganhas')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
        ];
    }
}
