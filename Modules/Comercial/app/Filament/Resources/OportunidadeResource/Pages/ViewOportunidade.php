<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

use Modules\Comercial\Filament\Resources\OportunidadeResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\ViewEntry;

class ViewOportunidade extends ViewRecord
{
    protected static string $resource = OportunidadeResource::class;
    protected string $view = 'comercial::filament.resources.oportunidade-resource.pages.view-oportunidade';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Resumo')
                    ->schema([
                        TextEntry::make('valor_estimado')->label('Valor')->money('BRL'),
                        TextEntry::make('user.name')->label('Proprietário'),
                        TextEntry::make('data_fechamento_esperada')->label('Fechamento Esperado')->date('d/m/Y'),
                    ])->collapsible()->columns(1),

                Section::make('Pessoa')
                    ->schema([
                        TextEntry::make('pessoa_contato_nome')->label('Nome do Contato')->default('-'),
                        TextEntry::make('pessoa_contato_telefone')->label('Telefone')->default('-'),
                        TextEntry::make('pessoa_contato_email')->label('E-mail')->default('-'),
                    ])->collapsible()->columns(1),

                Section::make('Organização')
                    ->schema([
                        TextEntry::make('fornecedor.razao_social')->label('Razão Social')->default('-'),
                        TextEntry::make('fornecedor.cnpj')->label('CNPJ')->default('-'),
                    ])->collapsible()->columns(1),

                Section::make('Produtos')
                    ->schema([
                        TextEntry::make('produtos.nome')->label('Itens vinculados')->listWithLineBreaks()->bulleted(),
                    ])->collapsible()->columns(1),
            ])->columns(1);
    }

    public $novaAnotacao = '';

    // Novas propriedades de abas
    public $activeTab = 'anotacoes';
    public $novaAtividadeTitulo = '';
    public $novaAtividadeData = '';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function salvarAtividade()
    {
        if (empty(trim($this->novaAtividadeTitulo)) || empty($this->novaAtividadeData)) return;

        \Modules\Comercial\Models\TarefaAgenda::create([
            'titulo' => $this->novaAtividadeTitulo,
            'data_inicio' => $this->novaAtividadeData,
            'status' => 'Pendente',
            'oportunidade_id' => $this->record->id,
        ]);

        $this->record->historicos()->create([
            'tipo' => 'sistema',
            'nota' => 'Atividade agendada: ' . $this->novaAtividadeTitulo . ' para ' . \Carbon\Carbon::parse($this->novaAtividadeData)->format('d/m/Y H:i'),
            'user_id' => auth()->id(),
        ]);

        $this->novaAtividadeTitulo = '';
        $this->novaAtividadeData = '';
        $this->activeTab = 'anotacoes';

        \Filament\Notifications\Notification::make()->title('Atividade agendada!')->success()->send();
    }

    public function salvarAnotacao()
    {
        if (empty(trim($this->novaAnotacao))) return;

        $this->record->historicos()->create([
            'tipo' => 'anotacao',
            'nota' => $this->novaAnotacao,
            'user_id' => auth()->id(),
        ]);

        $this->novaAnotacao = '';

        \Filament\Notifications\Notification::make()
            ->title('Anotação salva!')
            ->success()
            ->send();
    }

    public function marcarComoGanho()
    {
        $totalPropostas = $this->record->propostas()->count();
        $propostasAprovadas = $this->record->propostas()->where('status', 'Aprovada')->count();

        if ($totalPropostas === 0) {
            \Filament\Notifications\Notification::make()
                ->title('Atenção')
                ->body('Esta oportunidade não tem proposta vinculada. Crie uma proposta primeiro.')
                ->danger()
                ->send();
            return;
        }

        if ($propostasAprovadas === 0) {
            \Filament\Notifications\Notification::make()
                ->title('Atenção')
                ->body('Aprove uma proposta antes de dar como Ganho.')
                ->danger()
                ->send();
            return;
        }

        if ($this->record->status !== 'Fechado / Aprovado') {
            $this->record->update([
                'status' => 'Fechado / Aprovado',
                'data_fechamento_real' => now(),
            ]);

            $this->record->historicos()->create([
                'tipo' => 'sistema',
                'nota' => 'Oportunidade marcada como GANHA.',
                'user_id' => auth()->id(),
            ]);

            if (!$this->record->onboarding) {
                \Modules\Comercial\Models\Onboarding::create([
                    'oportunidade_id' => $this->record->id,
                    'fornecedor_id' => $this->record->fornecedor_id,
                    'titulo' => $this->record->titulo,
                    'status' => 'Transição de Vendas',
                    'valor_fechado' => $this->record->valor_estimado,
                    'data_venda' => now(),
                    'resumo_venda' => $this->record->descricao ?? '',
                ]);
            }

            \Filament\Notifications\Notification::make()
                ->title('Oportunidade Ganha!')
                ->success()
                ->send();
        }
    }

    public function marcarComoPerdido()
    {
        if ($this->record->status !== 'Perdido / Recusado') {
            $this->record->update([
                'status' => 'Perdido / Recusado',
                'data_fechamento_real' => now(),
            ]);

            $this->record->historicos()->create([
                'tipo' => 'sistema',
                'nota' => 'Oportunidade marcada como PERDIDA.',
                'user_id' => auth()->id(),
            ]);

            \Filament\Notifications\Notification::make()
                ->title('Oportunidade Perdida')
                ->danger()
                ->send();
        }
    }
}
