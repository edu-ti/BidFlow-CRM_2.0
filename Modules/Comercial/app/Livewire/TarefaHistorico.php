<?php

namespace Modules\Comercial\Livewire;

use Livewire\Component;
use Modules\Comercial\Models\Historico;

class TarefaHistorico extends Component
{
    public $tarefa_agenda_id;
    public $oportunidade_id;

    public $nova_nota = '';

    public function mount($tarefa_agenda_id, $oportunidade_id = null)
    {
        $this->tarefa_agenda_id = $tarefa_agenda_id;
        $this->oportunidade_id = $oportunidade_id;
    }

    public function salvarNota()
    {
        $this->validate([
            'nova_nota' => 'required|string',
        ]);

        Historico::create([
            'historicoable_type' => \Modules\Comercial\Models\TarefaAgenda::class,
            'historicoable_id' => $this->tarefa_agenda_id,
            'nota' => $this->nova_nota,
        ]);

        $this->nova_nota = '';
        $this->dispatch('notaCriada');
    }

    public function getHistoricosProperty()
    {
        $query = Historico::query();

        $query->where(function ($q) {
            $q->where(function ($subQ) {
                $subQ->where('historicoable_type', \Modules\Comercial\Models\TarefaAgenda::class)
                     ->where('historicoable_id', $this->tarefa_agenda_id);
            });
            
            if ($this->oportunidade_id) {
                $q->orWhere(function ($subQ) {
                    $subQ->where('historicoable_type', \Modules\Comercial\Models\Oportunidade::class)
                         ->where('historicoable_id', $this->oportunidade_id);
                });
            }
        });

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('comercial::livewire.tarefa-historico', [
            'historicos' => $this->historicos,
        ]);
    }
}
