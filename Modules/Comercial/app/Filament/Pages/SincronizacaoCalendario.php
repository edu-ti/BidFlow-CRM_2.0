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

    public function mount()
    {
        $this->syncStatus = !empty(auth()->user()->google_access_token);
        $this->syncType = auth()->user()->google_sync_mode ?? 'unidirectional';
    }

    public function updatedSyncType($value)
    {
        auth()->user()->update(['google_sync_mode' => $value]);
        \Filament\Notifications\Notification::make()
            ->title('Modo de sincronização atualizado')
            ->success()
            ->send();
    }

    public function toggleSync()
    {
        if ($this->syncStatus) {
            // Desconectar
            auth()->user()->update([
                'google_access_token' => null,
                'google_refresh_token' => null,
                'google_token_expires_at' => null,
            ]);
            $this->syncStatus = false;
            \Filament\Notifications\Notification::make()
                ->title('Sincronização Parada')
                ->danger()
                ->send();
        } else {
            // Conectar - Redirecionar para Google
            return redirect()->route('google.redirect');
        }
    }
}
