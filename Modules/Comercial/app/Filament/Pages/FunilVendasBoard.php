<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\Oportunidade;
use Filament\Notifications\Notification;

class FunilVendasBoard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-funnel';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.funil-vendas-board';
    protected static ?string $title = 'Funil de Vendas';
    protected static ?int $navigationSort = 2;

    public $stages = [
        'Prospectando',
        'Proposta',
        'Negociação',
        'Fechado / Aprovado',
        'Perdido / Recusado'
    ];

    public $oportunidades = [];

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('lista')
                ->label('Ver Lista')
                ->icon('heroicon-m-bars-4')
                ->color('gray')
                ->url(\Modules\Comercial\Filament\Resources\OportunidadeResource::getUrl('index')),
            \Filament\Actions\CreateAction::make('create')
                ->label('Nova Oportunidade')
                ->model(Oportunidade::class)
                ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\OportunidadeResource::form($form)->getComponents())
                ->using(function (array $data, string $model): \Illuminate\Database\Eloquent\Model {
                    $criarTarefa = $data['criar_tarefa'] ?? false;
                    $dataTarefa = $data['data_tarefa'] ?? null;
                    unset($data['criar_tarefa'], $data['data_tarefa']);

                    $record = $model::create($data);

                    if (isset($data['funil_selecionado']) && $data['funil_selecionado'] === 'Funil de onboarding') {
                        $record->update(['status' => 'Fechado / Aprovado', 'data_fechamento_real' => now()]);
                        if (!$record->onboarding) {
                            \Modules\Comercial\Models\Onboarding::create([
                                'oportunidade_id' => $record->id,
                                'fornecedor_id' => $record->fornecedor_id,
                                'titulo' => $record->titulo,
                                'status' => 'Transição de Vendas',
                                'valor_fechado' => $record->valor_estimado,
                                'data_venda' => now(),
                                'resumo_venda' => $record->descricao ?? '',
                            ]);
                        }
                    }

                    if ($criarTarefa && $dataTarefa) {
                        \Modules\Comercial\Models\TarefaAgenda::create([
                            'titulo' => 'Primeiro Contato - ' . $record->titulo,
                            'data_inicio' => $dataTarefa,
                            'status' => 'Pendente',
                            'oportunidade_id' => $record->id,
                        ]);
                    }

                    return $record;
                })
                ->after(function (\Illuminate\Database\Eloquent\Model $record) {
                    $totalProdutos = $record->oportunidadeProdutos()->sum(\Illuminate\Support\Facades\DB::raw('quantidade * preco_unitario'));
                    if ($totalProdutos > 0) {
                        $record->update(['valor_estimado' => $totalProdutos]);
                        if ($record->onboarding) {
                            $record->onboarding->update(['valor_fechado' => $totalProdutos]);
                        }
                    }
                })
                ->after(fn() => $this->loadOportunidades())
                ->color('primary'),
        ];
    }

    public function editOportunidadeAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\EditAction::make('editOportunidade')
            ->model(Oportunidade::class)
            ->record(fn (array $arguments) => Oportunidade::find($arguments['record']))
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\OportunidadeResource::form($form)->getComponents())
            ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                $criarTarefa = $data['criar_tarefa'] ?? false;
                $dataTarefa = $data['data_tarefa'] ?? null;
                unset($data['criar_tarefa'], $data['data_tarefa']);

                $record->update($data);

                if ($criarTarefa && $dataTarefa) {
                    \Modules\Comercial\Models\TarefaAgenda::create([
                        'titulo' => 'Acompanhamento - ' . $record->titulo,
                        'data_inicio' => $dataTarefa,
                        'status' => 'Pendente',
                        'oportunidade_id' => $record->id,
                    ]);
                }

                return $record;
            })
            ->extraModalFooterActions(fn (\Filament\Actions\EditAction $action): array => [
                \Filament\Actions\DeleteAction::make('delete')
                    ->record($action->getRecord())
                    ->cancelParentActions()
                    ->after(fn () => $this->loadOportunidades()),
            ])
            ->after(function (\Illuminate\Database\Eloquent\Model $record) {
                $totalProdutos = $record->oportunidadeProdutos()->sum(\Illuminate\Support\Facades\DB::raw('quantidade * preco_unitario'));
                if ($totalProdutos > 0) {
                    $record->update(['valor_estimado' => $totalProdutos]);
                    if ($record->onboarding) {
                        $record->onboarding->update(['valor_fechado' => $totalProdutos]);
                    }
                }
                $this->loadOportunidades();
            });
    }

    public function recusarOportunidadeAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('recusarOportunidade')
            ->modalHeading('Motivo da Perda')
            ->form([
                \Filament\Forms\Components\TextInput::make('motivo_perda')
                    ->label('Motivo')
                    ->required(),
            ])
            ->action(function (array $data, array $arguments) {
                $oportunidade = Oportunidade::find($arguments['id']);
                if ($oportunidade) {
                    $oportunidade->update([
                        'status' => 'Perdido / Recusado',
                        'motivo_perda' => $data['motivo_perda'],
                        'data_fechamento_real' => now(),
                    ]);
                    
                    $oportunidade->historicos()->create([
                        'tipo' => 'sistema',
                        'nota' => 'Oportunidade PERDIDA. Motivo: ' . $data['motivo_perda'],
                        'user_id' => auth()->id(),
                    ]);

                    $this->loadOportunidades();
                    Notification::make()->title('Sucesso')->body('Oportunidade movida para Perdido / Recusado')->success()->send();
                }
            });
    }

    public function createPropostaFromBoardAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('createPropostaFromBoard')
            ->modalHeading('Criar Proposta Comercial')
            ->form([
                \Filament\Forms\Components\Hidden::make('oportunidade_id'),
                \Filament\Forms\Components\Hidden::make('fornecedor_id'),
                \Filament\Forms\Components\TextInput::make('numero')->label('Número da Proposta')->required(),
                \Filament\Forms\Components\DatePicker::make('data_proposta')->label('Data da Proposta')->required(),
                \Filament\Forms\Components\DatePicker::make('validade')->label('Validade'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Em elaboração' => 'Em elaboração',
                        'Enviada' => 'Enviada',
                        'Em Negociação' => 'Em Negociação',
                        'Aprovada' => 'Aprovada',
                        'Recusada' => 'Recusada',
                    ])
                    ->default('Em elaboração')
                    ->required(),
            ])
            ->fillForm(function (array $arguments) {
                $oportunidade = Oportunidade::find($arguments['oportunidade_id'] ?? null);
                if ($oportunidade) {
                    return [
                        'oportunidade_id' => $oportunidade->id,
                        'fornecedor_id' => $oportunidade->fornecedor_id,
                        'status' => 'Em elaboração',
                        'data_proposta' => now()->format('Y-m-d'),
                        'numero' => 'PROP-' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    ];
                }
                return [];
            })
            ->action(function (array $data, array $arguments) {
                $proposta = \Modules\Comercial\Models\PropostaComercial::create($data);
                
                if (!empty($data['itens'])) {
                    foreach ($data['itens'] as $item) {
                        $proposta->itens()->create($item);
                    }
                }
                
                $oportunidade = Oportunidade::find($arguments['oportunidade_id']);
                if ($oportunidade) {
                    $oportunidade->update(['status' => 'Proposta']);
                    $oportunidade->historicos()->create([
                        'tipo' => 'sistema',
                        'nota' => 'Oportunidade movida para a fase: Proposta (Proposta ' . $proposta->numero . ' gerada)',
                        'user_id' => auth()->id(),
                    ]);
                }
                $this->loadOportunidades();
                Notification::make()->title('Sucesso')->body('Proposta criada e oportunidade atualizada.')->success()->send();
            })
            ->modalCancelAction(fn ($action) => $action->label('Cancelar'));
    }

    public function mount()
    {
        $this->loadOportunidades();
    }

    public function loadOportunidades()
    {
        $this->oportunidades = Oportunidade::with('fornecedor')->get()->toArray();
    }

    public function updateOportunidadeStatus($id, $newStage)
    {
        $oportunidade = Oportunidade::find($id);
        
        if ($oportunidade && $oportunidade->status !== $newStage) {
            if ($newStage === 'Perdido / Recusado') {
                $this->mountAction('recusarOportunidade', ['id' => $id]);
                return;
            }

            if ($newStage === 'Proposta' && $oportunidade->propostas()->count() === 0) {
                $this->mountAction('createPropostaFromBoard', ['oportunidade_id' => $id]);
                return;
            }

            if ($newStage === 'Negociação' && $oportunidade->propostas()->count() === 0) {
                Notification::make()->title('Atenção')->body('Crie uma proposta antes de avançar para Negociação.')->danger()->send();
                return;
            }

            if ($newStage === 'Fechado / Aprovado') {
                $totalPropostas = $oportunidade->propostas()->count();
                $propostasAprovadas = $oportunidade->propostas()->where('status', 'Aprovada')->count();

                if ($totalPropostas === 0) {
                    Notification::make()->title('Atenção')->body('Esta oportunidade não tem proposta vinculada. Crie uma proposta primeiro.')->danger()->send();
                    return;
                }

                if ($propostasAprovadas === 0) {
                    Notification::make()->title('Atenção')->body('Aprove uma proposta antes de dar como Fechado / Aprovado.')->danger()->send();
                    return;
                }
                $oportunidade->update([
                    'status' => $newStage,
                    'data_fechamento_real' => now(),
                ]);

                if (!$oportunidade->onboarding) {
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
            } else {
                $oportunidade->update(['status' => $newStage]);
            }

            $oportunidade->historicos()->create([
                'tipo' => 'sistema',
                'nota' => 'Oportunidade movida para a fase: ' . $newStage,
                'user_id' => auth()->id(),
            ]);
            
            $this->loadOportunidades();
            Notification::make()->title('Sucesso')->body('Oportunidade movida para ' . $newStage)->success()->send();
        }
    }
}
