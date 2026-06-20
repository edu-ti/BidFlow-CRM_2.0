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
            Actions\DeleteAction::make(),
        ];
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
}
