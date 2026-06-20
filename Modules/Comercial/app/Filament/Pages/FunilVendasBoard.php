<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\Oportunidade;
use Filament\Notifications\Notification;

class FunilVendasBoard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-funnel';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.funil-vendas-board';
    protected static ?string $title = 'Funil de Vendas';
    protected static ?int $navigationSort = 2;

    public $stages = [
        'Prospectando',
        'Proposta',
        'Negociação',
        'Fechado/Aprovado',
        'Controle de Entrega',
        'Treinamentos',
        'Pós-venda',
        'Recusado'
    ];

    public $oportunidades = [];

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make('create')
                ->label('Nova Oportunidade')
                ->model(Oportunidade::class)
                ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\OportunidadeResource::form($form)->getComponents())
                ->after(fn() => $this->loadOportunidades())
                ->color('primary'),
        ];
    }

    public function editOportunidadeAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\EditAction::make('editOportunidade')
            ->model(Oportunidade::class)
            ->record(fn (array $arguments) => Oportunidade::find($arguments['record']))
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\OportunidadeResource::form($form)->getComponents())
            ->extraModalFooterActions(fn (\Filament\Actions\EditAction $action): array => [
                \Filament\Actions\DeleteAction::make('delete')
                    ->record($action->getRecord())
                    ->cancelParentActions()
                    ->after(fn () => $this->loadOportunidades()),
            ])
            ->after(fn() => $this->loadOportunidades());
    }

    public function recusarOportunidadeAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('recusarOportunidade')
            ->modalHeading('Motivo da Perda')
            ->form([
                \Filament\Forms\Components\TextInput::make('motivo_perda')
                    ->label('Motivo')
                    ->required(),
            ])
            ->action(function (array $data, array $arguments) {
                $oportunidade = Oportunidade::find($arguments['id']);
                if ($oportunidade) {
                    $oportunidade->update([
                        'status' => 'Recusado',
                        'motivo_perda' => $data['motivo_perda'],
                        'data_fechamento_real' => now(),
                    ]);
                    $this->loadOportunidades();
                    Notification::make()->title('Sucesso')->body('Oportunidade movida para Recusado')->success()->send();
                }
            });
    }

    public function mount()
    {
        $this->loadOportunidades();
    }

    public function loadOportunidades()
    {
        $this->oportunidades = Oportunidade::with('fornecedor')->get()->toArray();
    }

    public function updateOportunidadeStatus($id, $newStage)
    {
        $oportunidade = Oportunidade::find($id);
        
        if ($oportunidade && $oportunidade->status !== $newStage) {
            if ($newStage === 'Recusado') {
                $this->mountAction('recusarOportunidade', ['id' => $id]);
                return;
            }

            if ($newStage === 'Negociação' && $oportunidade->propostas()->count() === 0) {
                Notification::make()->title('Atenção')->body('Crie uma proposta antes de avançar para Negociação.')->danger()->send();
                return;
            }

            if ($newStage === 'Fechado/Aprovado') {
                if ($oportunidade->propostas()->where('status', 'Aprovada')->count() === 0) {
                    Notification::make()->title('Atenção')->body('Aprove uma proposta antes de dar como Fechado/Aprovado.')->danger()->send();
                    return;
                }
                $oportunidade->update([
                    'status' => $newStage,
                    'data_fechamento_real' => now(),
                ]);
            } else {
                $oportunidade->update(['status' => $newStage]);
            }
            
            $this->loadOportunidades();
            Notification::make()->title('Sucesso')->body('Oportunidade movida para ' . $newStage)->success()->send();
        }
    }
}
