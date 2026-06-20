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
                    $this->loadOportunidades();
                    Notification::make()->title('Sucesso')->body('Oportunidade movida para Perdido / Recusado')->success()->send();
                }
            });
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

            if ($newStage === 'Negociação' && $oportunidade->propostas()->count() === 0) {
                Notification::make()->title('Atenção')->body('Crie uma proposta antes de avançar para Negociação.')->danger()->send();
                return;
            }

            if ($newStage === 'Fechado / Aprovado') {
                if ($oportunidade->propostas()->where('status', 'Aprovada')->count() === 0) {
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
            
            $this->loadOportunidades();
            Notification::make()->title('Sucesso')->body('Oportunidade movida para ' . $newStage)->success()->send();
        }
    }
}
