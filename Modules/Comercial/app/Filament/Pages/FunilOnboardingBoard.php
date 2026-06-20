<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\Onboarding;
use Filament\Notifications\Notification;

class FunilOnboardingBoard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rocket-launch';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.funil-onboarding-board';
    protected static ?string $title = 'Funil de Onboarding (CS)';
    protected static ?int $navigationSort = 3;

    public $stages = [
        'Transição de Vendas',
        'Reunião de Alinhamento',
        'Controle de Entrega',
        'Treinamentos',
        'Pós-venda'
    ];

    public $onboardings = [];

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function editOnboardingAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\EditAction::make('editOnboarding')
            ->model(Onboarding::class)
            ->record(fn (array $arguments) => Onboarding::find($arguments['record']))
            ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\OnboardingResource::form($form)->getComponents())
            ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                $record->update($data);
                return $record;
            })
            ->extraModalFooterActions(fn (\Filament\Actions\EditAction $action): array => [
                \Filament\Actions\DeleteAction::make('delete')
                    ->record($action->getRecord())
                    ->cancelParentActions()
                    ->after(fn () => $this->loadOnboardings()),
            ])
            ->after(fn() => $this->loadOnboardings());
    }

    public function mount()
    {
        $this->loadOnboardings();
    }

    public function loadOnboardings()
    {
        $this->onboardings = Onboarding::with('fornecedor')->get()->toArray();
    }

    public function updateOnboardingStatus($id, $newStage)
    {
        $onboarding = Onboarding::find($id);
        
        if ($onboarding && $onboarding->status !== $newStage) {
            if ($newStage === 'Pós-venda') {
                $onboarding->update([
                    'status' => $newStage,
                    'data_conclusao_real' => now(),
                ]);
            } else {
                $onboarding->update(['status' => $newStage]);
            }
            
            $this->loadOnboardings();
            Notification::make()->title('Sucesso')->body('Projeto movido para ' . $newStage)->success()->send();
        }
    }
}
