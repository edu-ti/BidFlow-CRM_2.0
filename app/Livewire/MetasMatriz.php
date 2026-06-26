<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Meta;
use App\Models\User;
use Modules\Fornecedores\Models\Fornecedor;
use Livewire\Attributes\On;

class MetasMatriz extends Component
{
    public $anoBase;
    public $vendedores = [];
    public $fornecedores = [];
    
    public $availableVendedores = [];
    public $availableFornecedores = [];
    
    public $todasUfs = [
        'AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG',
        'PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'
    ];

    public function mount()
    {
        $this->anoBase = date('Y');
        $this->availableVendedores = User::select('id', 'name')->get()->toArray();
        $this->availableFornecedores = Fornecedor::select('id', 'razao_social')->get()->toArray();
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->vendedores = [];
        $metasUser = Meta::where('tipo_entidade', 'user')->where('ano', $this->anoBase)->get();
        foreach ($metasUser as $meta) {
            $this->vendedores[] = [
                'user_id' => $meta->entidade_id,
                'valor' => $meta->valor,
                'fixo' => $meta->fixo,
                'comissao_percentual' => $meta->comissao_percentual,
                'ativo' => (bool)$meta->ativo,
            ];
        }
        
        $this->fornecedores = [];
        $metasForn = Meta::where('tipo_entidade', 'fornecedor')->where('ano', $this->anoBase)->get();
        $fornecedorIds = $metasForn->pluck('entidade_id')->unique();
        foreach ($fornecedorIds as $fid) {
            $fMetas = $metasForn->where('entidade_id', $fid);
            $estados = $fMetas->pluck('estado_uf')->filter()->unique()->values()->toArray();
            $estadosData = [];
            foreach ($estados as $uf) {
                $estadosData[$uf] = array_fill(1, 12, 0);
            }
            foreach ($fMetas as $fm) {
                if ($fm->estado_uf && $fm->mes) {
                    $estadosData[$fm->estado_uf][$fm->mes] = $fm->valor;
                }
            }
            $this->fornecedores[] = [
                'fornecedor_id' => $fid,
                'estados' => $estadosData,
                'novoEstado' => '',
            ];
        }
    }
    
    public function addVendedor()
    {
        $this->vendedores[] = [
            'user_id' => null,
            'valor' => 0,
            'fixo' => 0,
            'comissao_percentual' => 0,
            'ativo' => true,
        ];
    }

    public function removeVendedor($index)
    {
        unset($this->vendedores[$index]);
        $this->vendedores = array_values($this->vendedores);
    }
    
    public function addFornecedor()
    {
        $this->fornecedores[] = [
            'fornecedor_id' => null,
            'estados' => [],
            'novoEstado' => '',
        ];
    }

    public function removeFornecedor($index)
    {
        if (!empty($this->fornecedores[$index]['fornecedor_id'])) {
            Meta::where('tipo_entidade', 'fornecedor')
                ->where('entidade_id', $this->fornecedores[$index]['fornecedor_id'])
                ->where('ano', $this->anoBase)
                ->delete();
        }
        unset($this->fornecedores[$index]);
        $this->fornecedores = array_values($this->fornecedores);
    }

    public function addEstadoFornecedor($index)
    {
        $uf = $this->fornecedores[$index]['novoEstado'] ?? null;
        if ($uf && !isset($this->fornecedores[$index]['estados'][$uf])) {
            $this->fornecedores[$index]['estados'][$uf] = array_fill(1, 12, 0);
            $this->fornecedores[$index]['novoEstado'] = '';
        }
    }
    
    public function removeEstadoFornecedor($index, $uf)
    {
        if (isset($this->fornecedores[$index]['estados'][$uf])) {
            unset($this->fornecedores[$index]['estados'][$uf]);
        }
    }

    public function updatedAnoBase()
    {
        $this->loadData();
    }

    #[On('save-metas')]
    public function save()
    {
        Meta::where('tipo_entidade', 'user')->where('ano', $this->anoBase)->delete();
        foreach ($this->vendedores as $v) {
            if ($v['user_id']) {
                Meta::create([
                    'tipo_entidade' => 'user',
                    'entidade_id' => $v['user_id'],
                    'ano' => $this->anoBase,
                    'valor' => $v['valor'] ?: 0,
                    'fixo' => $v['fixo'] ?: 0,
                    'comissao_percentual' => $v['comissao_percentual'] ?: 0,
                    'ativo' => $v['ativo'],
                ]);
            }
        }
        
        foreach ($this->fornecedores as $f) {
            if ($f['fornecedor_id']) {
                Meta::where('tipo_entidade', 'fornecedor')
                    ->where('entidade_id', $f['fornecedor_id'])
                    ->where('ano', $this->anoBase)
                    ->delete();
                    
                foreach ($f['estados'] as $uf => $meses) {
                    foreach ($meses as $mes => $valor) {
                        if ($valor > 0) {
                            Meta::create([
                                'tipo_entidade' => 'fornecedor',
                                'entidade_id' => $f['fornecedor_id'],
                                'frequencia' => 'mensal',
                                'ano' => $this->anoBase,
                                'mes' => $mes,
                                'estado_uf' => $uf,
                                'valor' => $valor,
                            ]);
                        }
                    }
                }
            }
        }
        
        \Filament\Notifications\Notification::make()
            ->title('Matriz de Metas salva com sucesso!')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.metas-matriz');
    }
}
