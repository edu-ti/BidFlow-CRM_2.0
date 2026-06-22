<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;

class SincronizacaoCalendario extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $title = 'Sincronização de calendário';
    protected static \UnitEnum|string|null $navigationGroup = 'Minha Conta';
    protected static ?int $navigationSort = 2;
    
    protected string $view = 'comercial::filament.pages.sincronizacao-calendario';

    public $syncStatus = false;
    public $syncType = 'bidirectional';
    public $conversionType = 'Reunião';
    public $activitiesToSync = ['Chamada', 'Reunião', 'Tarefa', 'Prazo', 'E-mail', 'Almoço'];
    public $privateEvents = 'Apenas eu';
    public $advancedSync = 'no_contact';

    public function toggleSync()
    {
        $this->syncStatus = !$this->syncStatus;
        if ($this->syncStatus) {
            \Filament\Notifications\Notification::make()
                ->title('Sincronização Ativada')
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Sincronização Parada')
                ->danger()
                ->send();
        }
    }
}
