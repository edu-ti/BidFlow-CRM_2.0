<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\TarefaAgenda;
use Illuminate\Support\Carbon;

class AgendaSemanaBoard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.agenda-semana-board';
    protected static ?string $title = 'Agenda da Semana';
    protected static ?int $navigationSort = 1;

    public $weekStart;
    public $weekEnd;
    public $days = [];
    public $tempActionData = [];

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make('create')
                ->label('Nova Tarefa')
                ->model(TarefaAgenda::class)
                ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\TarefaAgendaResource::form($form)->getComponents())
                ->mutateFormDataUsing(function (array $data) {
                    $this->tempActionData = $data;
                    unset($data['etapa_funil'], $data['acao_oportunidade']);
                    return $data;
                })
                ->after(function (\Filament\Actions\CreateAction $action, $record) {
                    $data = $this->tempActionData;
                    if (!empty($data['etapa_funil'])) {
                        $oportunidade = \Modules\Comercial\Models\Oportunidade::create([
                            'titulo' => $record->titulo,
                            'fornecedor_id' => $record->fornecedor_id,
                            'status' => $data['etapa_funil'],
                            'valor_estimado' => 0,
                        ]);
                        $record->update(['oportunidade_id' => $oportunidade->id]);
                    }
                    $this->loadDays();
                })
                ->color('primary'),
        ];
    }

    public function editTarefaAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\EditAction::make('editTarefa')
            ->model(TarefaAgenda::class)
            ->record(fn (array $arguments) => TarefaAgenda::find($arguments['record']))
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\TarefaAgendaResource::form($form)->getComponents())
            ->mutateFormDataUsing(function (array $data) {
                $this->tempActionData = $data;
                unset($data['etapa_funil'], $data['acao_oportunidade']);
                return $data;
            })
            ->extraModalFooterActions(fn (\Filament\Actions\EditAction $action): array => [
                \Filament\Actions\DeleteAction::make('delete')
                    ->record($action->getRecord())
                    ->cancelParentActions()
                    ->after(fn () => $this->loadDays()),
            ])
            ->after(function (\Filament\Actions\EditAction $action, $record) {
                $data = $this->tempActionData;
                if (!empty($data['etapa_funil'])) {
                    if ($record->oportunidade_id) {
                        if (isset($data['acao_oportunidade']) && $data['acao_oportunidade'] === 'criar') {
                            $oportunidade = \Modules\Comercial\Models\Oportunidade::create([
                                'titulo' => $record->titulo,
                                'fornecedor_id' => $record->fornecedor_id,
                                'status' => $data['etapa_funil'],
                                'valor_estimado' => 0,
                            ]);
                            $record->update(['oportunidade_id' => $oportunidade->id]);
                        } else {
                            $record->oportunidade->update(['status' => $data['etapa_funil']]);
                        }
                    } else {
                        $oportunidade = \Modules\Comercial\Models\Oportunidade::create([
                            'titulo' => $record->titulo,
                            'fornecedor_id' => $record->fornecedor_id,
                            'status' => $data['etapa_funil'],
                            'valor_estimado' => 0,
                        ]);
                        $record->update(['oportunidade_id' => $oportunidade->id]);
                    }
                }
                $this->loadDays();
            });
    }

    public function mount()
    {
        $this->weekStart = now()->startOfWeek();
        $this->loadDays();
    }

    public function loadDays()
    {
        $this->days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $this->weekStart->copy()->addDays($i);
            
            $tarefas = TarefaAgenda::with(['fornecedor', 'oportunidade'])
                ->whereDate('data_inicio', $date->format('Y-m-d'))
                ->get();

            $this->days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->locale('pt_BR')->translatedFormat('l (d/m)'),
                'tarefas' => $tarefas,
            ];
        }
    }

    public function previousWeek()
    {
        $this->weekStart->subWeek();
        $this->loadDays();
    }

    public function nextWeek()
    {
        $this->weekStart->addWeek();
        $this->loadDays();
    }

    public function currentWeek()
    {
        $this->weekStart = now()->startOfWeek();
        $this->loadDays();
    }

    public function moveTask($taskId, $newDate)
    {
        $tarefa = TarefaAgenda::find($taskId);
        if ($tarefa) {
            $time = Carbon::parse($tarefa->data_inicio)->format('H:i:s');
            $tarefa->update([
                'data_inicio' => $newDate . ' ' . $time
            ]);
            $this->loadDays();
        }
    }
}
