<?php

namespace Modules\Licitacoes\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Licitacoes\Models\Licitacao;
use Illuminate\Support\Collection;

class RelatorioPregoes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static \UnitEnum|string|null $navigationGroup = 'Licitações';
    protected static ?string $title = 'Relatório de Pregões';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'relatorio-pregoes';

    protected string $view = 'licitacoes::filament.pages.relatorio-pregoes';

    public ?array $data = [];
    public ?Collection $licitacoesFiltradas = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Section::make('Filtros do Relatório')
                    ->schema([
                        Grid::make(3)->schema([
                            DatePicker::make('data_inicial')
                                ->label('Data Inicial (Disputa)'),
                            DatePicker::make('data_final')
                                ->label('Data Final (Disputa)'),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Em análise' => 'Em análise',
                                    'Acolhimento de propostas' => 'Acolhimento de propostas',
                                    'Homologado' => 'Homologado',
                                    'Adjudicado' => 'Adjudicado',
                                    'Revogado' => 'Revogado',
                                    'Fracassado' => 'Fracassado',
                                    'Anulado' => 'Anulado',
                                    'Suspenso' => 'Suspenso',
                                ])
                                ->searchable(),
                        ]),
                    ])
            ])
            ->statePath('data');
    }

    public function gerarRelatorio()
    {
        $data = $this->form->getState();

        $query = Licitacao::with(['itens.participantes.fornecedor']);

        if (!empty($data['data_inicial'])) {
            $query->whereDate('data_disputa', '>=', $data['data_inicial']);
        }

        if (!empty($data['data_final'])) {
            $query->whereDate('data_disputa', '<=', $data['data_final']);
        }

        if (!empty($data['status'])) {
            $query->where('status', $data['status']);
        }

        $this->licitacoesFiltradas = $query->orderBy('data_disputa', 'asc')->get();
    }

    public function limparFiltros()
    {
        $this->form->fill();
        $this->licitacoesFiltradas = null;
    }
}
