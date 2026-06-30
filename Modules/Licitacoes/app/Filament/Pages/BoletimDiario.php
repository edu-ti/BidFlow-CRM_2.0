<?php

namespace Modules\Licitacoes\Filament\Pages;

use Filament\Pages\Page;
use Modules\Licitacoes\Models\Boletim;
use Illuminate\Support\Collection;

class BoletimDiario extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Licitações';

    protected string $view = 'licitacoes::filament.pages.boletim-diario';
    
    protected static ?string $title = 'Boletim de Licitações';
    
    protected static ?int $navigationSort = 2;

    public ?Boletim $boletimAtivo = null;
    public Collection $boletinsDisponiveis;
    public Collection $oportunidades;

    public function mount()
    {
        $this->boletinsDisponiveis = Boletim::orderBy('data_geracao', 'desc')->take(10)->get();
        
        if ($this->boletinsDisponiveis->isNotEmpty()) {
            $this->selecionarBoletim($this->boletinsDisponiveis->first()->id);
        } else {
            $this->oportunidades = collect();
        }
    }
    
    public function selecionarBoletim($id)
    {
        $this->boletimAtivo = Boletim::with('oportunidades')->find($id);
        $this->oportunidades = $this->boletimAtivo ? $this->boletimAtivo->oportunidades : collect();
    }
}
