<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\PropostaComercialItem;
use Modules\Comercial\Models\Produto;

class FunilFornecedoresBoard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.funil-fornecedores-board';
    protected static ?string $title = 'Funil de Fornecedores';
    protected static ?int $navigationSort = 3;

    public $year;
    public $fabricante_id = 'Todos';
    public $fabricantes = [];
    public $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    public $itensMes = [];

    public function mount()
    {
        $this->year = date('Y');
        $fornecedores = \Modules\Fornecedores\Models\Fornecedor::where('exibir_no_funil_fornecedores', true)->orderBy('razao_social')->get();
        $this->fabricantes = ['Todos' => 'Todos'];
        foreach ($fornecedores as $f) {
            $this->fabricantes[$f->id] = !empty($f->nome_fantasia) ? $f->nome_fantasia : $f->razao_social;
        }
        
        $this->loadBoard();
    }

    public function setFabricante($id)
    {
        $this->fabricante_id = $id;
        $this->loadBoard();
    }

    public function previousYear()
    {
        $this->year--;
        $this->loadBoard();
    }

    public function nextYear()
    {
        $this->year++;
        $this->loadBoard();
    }

    public function loadBoard()
    {
        $query = PropostaComercialItem::with(['proposta.fornecedor', 'produto'])
            ->whereHas('proposta', function ($q) {
                $q->where('status', 'Aprovada')
                  ->whereYear('data_proposta', $this->year);
            });

        if ($this->fabricante_id !== 'Todos') {
            $query->whereHas('proposta', function ($q) {
                $q->where('fornecedor_id', $this->fabricante_id);
            });
        }

        $itens = $query->get();

        $this->itensMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->itensMes[$i] = $itens->filter(function($item) use ($i) {
                return \Carbon\Carbon::parse($item->proposta->data_proposta)->month == $i;
            })->values()->all();
        }
    }
}
