<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;

class GoogleCalendarController extends Controller
{
    public function redirect(GoogleCalendarService $googleCalendarService)
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            \Filament\Notifications\Notification::make()
                ->title('Credenciais Ausentes')
                ->body('Você precisa configurar as chaves do Google no arquivo .env antes de conectar.')
                ->danger()
                ->send();
                
            return back();
        }

        return redirect()->away($googleCalendarService->getAuthUrl());
    }

    public function callback(Request $request, GoogleCalendarService $googleCalendarService)
    {
        if ($request->has('code')) {
            $token = $googleCalendarService->authenticate($request->get('code'));
            
            if (!isset($token['error'])) {
                $user = auth()->user();
                $user->update([
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'] ?? $user->google_refresh_token,
                    'google_token_expires_at' => now()->addSeconds($token['expires_in']),
                ]);
                
                \Filament\Notifications\Notification::make()
                    ->title('Google Agenda conectado com sucesso!')
                    ->success()
                    ->send();
            } else {
                \Filament\Notifications\Notification::make()
                    ->title('Erro ao conectar Google Agenda.')
                    ->danger()
                    ->send();
            }
        }

        // Retorna para a página de configurações de sincronização
        return redirect()->route('filament.admin.pages.sincronizacao-calendario');
    }

    public function disconnect()
    {
        $user = auth()->user();
        $user->update([
            'google_access_token' => null,
            'google_refresh_token' => null,
            'google_token_expires_at' => null,
        ]);
        
        \Filament\Notifications\Notification::make()
            ->title('Google Agenda desconectado.')
            ->success()
            ->send();

        return back();
    }
}
