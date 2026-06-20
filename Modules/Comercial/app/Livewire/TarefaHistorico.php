<?php

namespace Modules\Comercial\Livewire;

use Livewire\Component;
use Modules\Comercial\Models\Historico;

class TarefaHistorico extends Component
{
    public $tarefa_agenda_id;
    public $oportunidade_id;
    public $fornecedor_id;

    public $nova_nota = '';

    public function mount($tarefa_agenda_id, $oportunidade_id = null, $fornecedor_id = null)
    {
        $this->tarefa_agenda_id = $tarefa_agenda_id;
        $this->oportunidade_id = $oportunidade_id;
        $this->fornecedor_id = $fornecedor_id;
    }

    public function salvarNota()
    {
        $this->validate([
            'nova_nota' => 'required|string',
        ]);

        Historico::create([
            'tarefa_agenda_id' => $this->tarefa_agenda_id,
            'oportunidade_id' => $this->oportunidade_id,
            'fornecedor_id' => $this->fornecedor_id,
            'nota' => $this->nova_nota,
        ]);

        $this->nova_nota = '';
        $this->dispatch('notaCriada');
    }

    public function getHistoricosProperty()
    {
        $query = Historico::query();

        if ($this->oportunidade_id) {
            $query->where('oportunidade_id', $this->oportunidade_id)
                  ->orWhere('tarefa_agenda_id', $this->tarefa_agenda_id);
        } else {
            $query->where('tarefa_agenda_id', $this->tarefa_agenda_id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('comercial::livewire.tarefa-historico', [
            'historicos' => $this->historicos,
        ]);
    }
}
