<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

use Modules\Comercial\Filament\Resources\OportunidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOportunidade extends CreateRecord
{
    protected static string $resource = OportunidadeResource::class;

    protected bool $criarTarefa = false;
    protected ?string $dataTarefa = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->criarTarefa = $data['criar_tarefa'] ?? false;
        $this->dataTarefa = $data['data_tarefa'] ?? null;
        unset($data['criar_tarefa'], $data['data_tarefa']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $totalProdutos = $this->record->oportunidadeProdutos()->sum(\Illuminate\Support\Facades\DB::raw('quantidade * preco_unitario'));

        if ($totalProdutos > 0) {
            $this->record->update(['valor_estimado' => $totalProdutos]);
            
            // Update the onboarding record if it was created
            if ($this->record->onboarding) {
                $this->record->onboarding->update(['valor_fechado' => $totalProdutos]);
            }
        }

        if ($this->record->funil_selecionado === 'Funil de onboarding') {
            $this->record->update(['status' => 'Fechado / Aprovado', 'data_fechamento_real' => now()]);
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

        if ($this->criarTarefa && $this->dataTarefa) {
            \Modules\Comercial\Models\TarefaAgenda::create([
                'titulo' => 'Primeiro Contato - ' . $this->record->titulo,
                'data_inicio' => $this->dataTarefa,
                'status' => 'Pendente',
                'oportunidade_id' => $this->record->id,
            ]);
        }
    }
}
