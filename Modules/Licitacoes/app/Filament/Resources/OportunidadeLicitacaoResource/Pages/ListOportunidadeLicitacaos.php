<?php

namespace Modules\Licitacoes\Filament\Resources\OportunidadeLicitacaoResource\Pages;

use Modules\Licitacoes\Filament\Resources\OportunidadeLicitacaoResource;
use Filament\Resources\Pages\Page;
use Modules\Licitacoes\Models\OportunidadeLicitacao;
use Livewire\WithPagination;
use Modules\Licitacoes\Models\Licitacao;
use Filament\Notifications\Notification;

class ListOportunidadeLicitacaos extends Page
{
    use WithPagination;

    protected static string $resource = OportunidadeLicitacaoResource::class;

    protected string $view = 'licitacoes::filament.resources.oportunidade-licitacao-resource.pages.list-oportunidades';

    public $searchObjeto = '';
    public $buscaExata = false;
    public $estado = '';
    public $cidade = '';
    public $numeroEdital = '';
    public $modalidade = '';
    public $perPage = 20;

    protected $queryString = [
        'searchObjeto' => ['except' => ''],
        'estado' => ['except' => ''],
        'cidade' => ['except' => ''],
        'numeroEdital' => ['except' => ''],
        'modalidade' => ['except' => ''],
        'perPage' => ['except' => 20],
    ];

    public function updating($name, $value)
    {
        if (in_array($name, ['searchObjeto', 'estado', 'cidade', 'numeroEdital', 'modalidade', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['searchObjeto', 'buscaExata', 'estado', 'cidade', 'numeroEdital', 'modalidade']);
        $this->resetPage();
    }

    public function getOportunidadesProperty()
    {
        $query = OportunidadeLicitacao::query()
            ->where('gerenciada', false)
            ->orderBy('id', 'desc');

        if ($this->searchObjeto) {
            if ($this->buscaExata) {
                $query->where('objeto', $this->searchObjeto);
            } else {
                $query->where('objeto', 'like', '%' . $this->searchObjeto . '%');
            }
        }
        if ($this->estado) {
            $query->where('estado', $this->estado);
        }
        if ($this->cidade) {
            $query->where('cidade', 'like', '%' . $this->cidade . '%');
        }
        if ($this->numeroEdital) {
            $query->where('edital', 'like', '%' . $this->numeroEdital . '%');
        }
        if ($this->modalidade) {
            $query->where('modalidade', $this->modalidade);
        }

        return $query->paginate($this->perPage);
    }

    public function toggleFavorito($id)
    {
        $oportunidade = OportunidadeLicitacao::find($id);
        if ($oportunidade) {
            $oportunidade->favorito = !$oportunidade->favorito;
            $oportunidade->save();
        }
    }

    public function gerenciarLicitacao($id)
    {
        $record = OportunidadeLicitacao::find($id);
        if ($record && !$record->gerenciada) {
            // Promove a Oportunidade para Licitação
            Licitacao::create([
                'numero_edital' => $record->edital,
                'numero_processo' => $record->processo,
                'modalidade' => $record->modalidade,
                'orgao_razao_social' => $record->orgao,
                'estado' => $record->estado,
                'cidade' => $record->cidade,
                'uasg' => $record->uasg,
                'data_disputa' => $record->data_abertura ? $record->data_abertura->format('Y-m-d') : null,
                'hora_disputa' => $record->data_abertura ? $record->data_abertura->format('H:i') : null,
                'status' => 'Em análise',
                'valor_estimado' => $record->valor_estimado,
            ]);
            
            $record->update(['gerenciada' => true]);
            
            Notification::make()
                ->title('Importado com sucesso!')
                ->body('A licitação agora está no seu painel de gestão.')
                ->success()
                ->send();
        }
    }
}
