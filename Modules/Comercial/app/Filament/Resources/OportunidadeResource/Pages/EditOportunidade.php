<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

use Modules\Comercial\Filament\Resources\OportunidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOportunidade extends EditRecord
{
    protected static string $resource = OportunidadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Voltar')
                ->icon('heroicon-m-arrow-left')
                ->color('gray')
                ->url(route('filament.admin.pages.funil-vendas-board')),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.pages.funil-vendas-board');
    }

    protected bool $criarTarefa = false;
    protected ?string $dataTarefa = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->criarTarefa = $data['criar_tarefa'] ?? false;
        $this->dataTarefa = $data['data_tarefa'] ?? null;
        unset($data['criar_tarefa'], $data['data_tarefa']);
        return $data;
    }

    protected function afterSave(): void
    {
        $totalProdutos = $this->record->oportunidadeProdutos()->sum(\Illuminate\Support\Facades\DB::raw('quantidade * preco_unitario'));
        if ($totalProdutos > 0) {
            $this->record->update(['valor_estimado' => $totalProdutos]);
            if ($this->record->onboarding) {
                $this->record->onboarding->update(['valor_fechado' => $totalProdutos]);
            }
        }

        if ($this->criarTarefa && $this->dataTarefa) {
            \Modules\Comercial\Models\TarefaAgenda::create([
                'titulo' => 'Acompanhamento - ' . $this->record->titulo,
                'data_inicio' => $this->dataTarefa,
                'status' => 'Pendente',
                'oportunidade_id' => $this->record->id,
            ]);
        }
    }

    public function createPropostaFromFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('createPropostaFromForm')
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
            ->fillForm(function () {
                return [
                    'oportunidade_id' => $this->record->id,
                    'fornecedor_id' => $this->record->fornecedor_id,
                    'status' => 'Em elaboração',
                    'data_proposta' => now()->format('Y-m-d'),
                    'numero' => 'PROP-' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                ];
            })
            ->action(function (array $data) {
                $proposta = \Modules\Comercial\Models\PropostaComercial::create($data);
                
                if (!empty($data['itens'])) {
                    foreach ($data['itens'] as $item) {
                        $proposta->itens()->create($item);
                    }
                }
                
                $dataForm = $this->form->getState();
                $dataForm['status'] = 'Proposta';
                $this->form->fill($dataForm);
                
                $this->record->update(['status' => 'Proposta']);
                $this->record->historicos()->create([
                    'tipo' => 'sistema',
                    'nota' => 'Oportunidade movida para a fase: Proposta (Proposta ' . $proposta->numero . ' gerada)',
                    'user_id' => auth()->id(),
                ]);
                
                \Filament\Notifications\Notification::make()->title('Sucesso')->body('Proposta criada e status atualizado.')->success()->send();
            })
            ->modalCancelAction(fn ($action) => $action->label('Cancelar'));
    }
}
