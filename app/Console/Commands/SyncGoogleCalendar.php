<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncGoogleCalendar extends Command
{
    protected $signature = 'google:sync-calendar';
    protected $description = 'Sincroniza eventos bidirecionais do Google Agenda para o CRM';

    public function handle()
    {
        $users = \App\Models\User::whereNotNull('google_access_token')
            ->where('google_sync_mode', 'bidirectional')
            ->get();

        if ($users->isEmpty()) {
            $this->info('Nenhum usuário configurado para sincronização bidirecional.');
            return;
        }

        $service = app(\App\Services\GoogleCalendarService::class);

        foreach ($users as $user) {
            $this->info("Sincronizando usuário: {$user->email}");
            $service->syncFromGoogle($user);
        }

        $this->info('Sincronização concluída!');
    }
}
