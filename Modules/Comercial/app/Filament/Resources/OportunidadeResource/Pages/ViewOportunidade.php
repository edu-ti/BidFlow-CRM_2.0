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

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Voltar')
                ->icon('heroicon-m-arrow-left')
                ->color('gray')
                ->url(route('filament.admin.pages.funil-vendas-board')),
        ];
    }

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
        $this->record->update(['status' => 'Perdido / Recusado']);
        $this->record->historicos()->create([
            'tipo' => 'sistema',
            'nota' => 'Oportunidade marcada como PERDIDA.',
            'user_id' => auth()->id(),
        ]);
        \Filament\Notifications\Notification::make()->title('Atualizado')->body('Oportunidade Perdida.')->success()->send();
    }

    public function mudarStatus($newStage)
    {
        $oportunidade = $this->record;
        
        if ($oportunidade && $oportunidade->status !== $newStage) {
            // Validation rules
            if ($newStage === 'Proposta' && $oportunidade->propostas()->count() === 0) {
                \Filament\Notifications\Notification::make()->title('Atenção')->body('Crie uma Proposta vinculada antes de mover para a fase de Proposta.')->danger()->send();
                return;
            }

            if ($newStage === 'Negociação' && $oportunidade->propostas()->count() === 0) {
                \Filament\Notifications\Notification::make()->title('Atenção')->body('Crie uma proposta antes de avançar para Negociação.')->danger()->send();
                return;
            }

            if ($newStage === 'Fechado / Aprovado') {
                $totalPropostas = $oportunidade->propostas()->count();
                $propostasAprovadas = $oportunidade->propostas()->where('status', 'Aprovada')->count();

                if ($totalPropostas > 0 && $propostasAprovadas === 0) {
                    \Filament\Notifications\Notification::make()->title('Atenção')->body('Você precisa ter pelo menos uma proposta aprovada para ganhar o negócio.')->danger()->send();
                    return;
                }

                $oportunidade->update([
                    'status' => $newStage,
                    'data_fechamento_real' => now(),
                ]);

                if (!$oportunidade->onboarding && $oportunidade->funil_selecionado === 'Funil de onboarding') {
                    \Modules\Comercial\Models\Onboarding::create([
                        'oportunidade_id' => $oportunidade->id,
                        'fornecedor_id' => $oportunidade->fornecedor_id,
                        'titulo' => $oportunidade->titulo,
                        'status' => 'Transição de Vendas',
                        'valor_fechado' => $oportunidade->valor_estimado,
                        'data_venda' => now(),
                        'resumo_venda' => $oportunidade->descricao,
                    ]);
                }
                
                $this->record->historicos()->create([
                    'tipo' => 'sistema',
                    'nota' => 'Oportunidade marcada como GANHA via clique no pipeline.',
                    'user_id' => auth()->id(),
                ]);

                \Filament\Notifications\Notification::make()->title('Sucesso')->body('Negócio Ganho!')->success()->send();
                return;
            }
            
            $oportunidade->update(['status' => $newStage]);
            $this->record->historicos()->create([
                'tipo' => 'sistema',
                'nota' => "Oportunidade movida para a fase: {$newStage}",
                'user_id' => auth()->id(),
            ]);

            \Filament\Notifications\Notification::make()->title('Sucesso')->body("Fase alterada para {$newStage}.")->success()->send();
        }
    }

    public function createPropostaFullAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\CreateAction::make('createPropostaFullAction')
            ->model(\Modules\Comercial\Models\PropostaComercial::class)
            ->modalHeading('Nova Proposta Comercial')
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\PropostaComercialResource::form($form)->getComponents())
            ->fillForm(function () {
                $produtos = [];
                $this->record->load('oportunidadeProdutos.produto');
                foreach ($this->record->oportunidadeProdutos as $opProd) {
                    $produto = $opProd->produto;
                    $q = (float)($opProd->quantidade ?: 0);
                    $v = (float)($opProd->preco_unitario ?: 0);
                    $produtos[] = [
                        'produto_id' => $opProd->produto_id,
                        'descricao' => $produto ? $produto->nome : '',
                        'fabricante' => $produto ? $produto->fabricante : '',
                        'modelo' => $produto ? $produto->modelo : '',
                        'tipo' => 'Venda',
                        'quantidade' => $q,
                        'valor_unitario' => $v,
                        'desconto_percentual' => 0,
                        'meses_locacao' => 1,
                        'valor_total' => round($q * $v, 2),
                        'unidade_medida' => $produto->unidade ?? 'Unidade',
                        'imagem' => $produto ? $produto->imagem_path : null,
                    ];
                }
                
                return [
                    'oportunidade_id' => $this->record->id,
                    'fornecedor_id' => $this->record->fornecedor_id,
                    'data_proposta' => now()->format('Y-m-d'),
                    'status' => 'Em elaboração',
                    'itens' => $produtos,
                    'termos_comerciais' => [
                        'faturamento' => 'Realizado diretamente pela fábrica.',
                        'treinamento' => 'Capacitação técnica por especialistas da empresa.',
                        'condicoes_pagamento' => 'A vista',
                        'prazo_entrega' => 'Até 30 dias após a confirmação do pedido de compra.',
                        'garantia_equipamentos' => '12 meses a partir da data de emissão da nota fiscal.',
                        'garantia_acessorios' => '6 meses, conforme especificações do fabricante.',
                        'instalacao' => 'Realizada pela equipe técnica da empresa, garantindo conformidade e segurança.',
                        'assistencia_tecnica' => 'Disponível com suporte especializado para manutenção e pós garantia.',
                        'observacoes_termos' => 'Nenhuma',
                    ],
                ];
            })
            ->mutateFormDataUsing(function (array $data) {
                // Ensure IDs are retained
                $data['oportunidade_id'] = $this->record->id;
                $data['fornecedor_id'] = $this->record->fornecedor_id;
                return $data;
            })
            ->after(function () {
                $this->record->refresh();
                \Filament\Notifications\Notification::make()->title('Sucesso')->body('Proposta criada!')->success()->send();
            })
            ->modalCancelAction(fn ($action) => $action->label('Cancelar'));
    }

    public function editPropostaFullAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\EditAction::make('editPropostaFullAction')
            ->record(fn (array $arguments) => \Modules\Comercial\Models\PropostaComercial::find($arguments['record']))
            ->modalHeading(fn ($record) => 'Editar Proposta ' . ($record->numero ? '#' . $record->numero : ''))
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\PropostaComercialResource::form($form)->getComponents())
            ->mutateRecordDataUsing(function (array $data): array {
                $defaults = [
                    'faturamento' => 'Realizado diretamente pela fábrica.',
                    'treinamento' => 'Capacitação técnica por especialistas da empresa.',
                    'condicoes_pagamento' => 'A vista',
                    'prazo_entrega' => 'Até 30 dias após a confirmação do pedido de compra.',
                    'garantia_equipamentos' => '12 meses a partir da data de emissão da nota fiscal.',
                    'garantia_acessorios' => '6 meses, conforme especificações do fabricante.',
                    'instalacao' => 'Realizada pela equipe técnica da empresa, garantindo conformidade e segurança.',
                    'assistencia_tecnica' => 'Disponível com suporte especializado para manutenção e pós garantia.',
                    'observacoes_termos' => 'Nenhuma',
                ];

                if (!isset($data['termos_comerciais']) || !is_array($data['termos_comerciais'])) {
                    $data['termos_comerciais'] = [];
                }

                foreach ($defaults as $key => $value) {
                    if (empty($data['termos_comerciais'][$key])) {
                        $data['termos_comerciais'][$key] = $value;
                    }
                }

                return $data;
            })
            ->after(function () {
                $this->record->refresh();
                \Filament\Notifications\Notification::make()->title('Sucesso')->body('Proposta atualizada!')->success()->send();
            })
            ->modalCancelAction(fn ($action) => $action->label('Cancelar'));
    }
}
