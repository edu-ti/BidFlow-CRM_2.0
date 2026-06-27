<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\User;
use Modules\Comercial\Models\TarefaAgenda;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(route('google.callback'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function refreshTokenIfNeeded(User $user)
    {
        $this->setAccessToken([
            'access_token' => $user->google_access_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in' => $user->google_token_expires_at ? $user->google_token_expires_at->diffInSeconds(now()) : 0,
        ]);

        if ($this->client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                if (!isset($newToken['error'])) {
                    $user->update([
                        'google_access_token' => $newToken['access_token'],
                        'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
                    ]);
                    $this->setAccessToken($newToken);
                }
            }
        }
    }

    public function createEvent(TarefaAgenda $tarefa, User $user)
    {
        if (!$user->google_access_token) return;

        $this->refreshTokenIfNeeded($user);
        $service = new Calendar($this->client);

        $event = new Event([
            'summary' => $tarefa->titulo,
            'description' => $tarefa->descricao ?? '',
            'start' => [
                'dateTime' => \Carbon\Carbon::parse($tarefa->data_inicio)->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'dateTime' => $tarefa->data_fim ? \Carbon\Carbon::parse($tarefa->data_fim)->toRfc3339String() : \Carbon\Carbon::parse($tarefa->data_inicio)->addHour()->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ],
        ]);

        try {
            $createdEvent = $service->events->insert('primary', $event);
            $tarefa->updateQuietly(['google_event_id' => $createdEvent->getId()]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar evento no Google Calendar: ' . $e->getMessage());
        }
    }

    public function updateEvent(TarefaAgenda $tarefa, User $user)
    {
        if (!$user->google_access_token || !$tarefa->google_event_id) return;

        $this->refreshTokenIfNeeded($user);
        $service = new Calendar($this->client);

        try {
            $event = $service->events->get('primary', $tarefa->google_event_id);
            $event->setSummary($tarefa->titulo);
            $event->setDescription($tarefa->descricao ?? '');
            
            $start = new EventDateTime();
            $start->setDateTime(\Carbon\Carbon::parse($tarefa->data_inicio)->toRfc3339String());
            $start->setTimeZone(config('app.timezone'));
            $event->setStart($start);

            $end = new EventDateTime();
            $end->setDateTime($tarefa->data_fim ? \Carbon\Carbon::parse($tarefa->data_fim)->toRfc3339String() : \Carbon\Carbon::parse($tarefa->data_inicio)->addHour()->toRfc3339String());
            $end->setTimeZone(config('app.timezone'));
            $event->setEnd($end);

            $service->events->update('primary', $event->getId(), $event);
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                // Evento não encontrado, tenta criar novamente
                $this->createEvent($tarefa, $user);
            } else {
                \Log::error('Erro ao atualizar evento no Google Calendar: ' . $e->getMessage());
            }
        }
    }

    public function deleteEvent($eventId, User $user)
    {
        if (!$user->google_access_token || !$eventId) return;

        $this->refreshTokenIfNeeded($user);
        $service = new Calendar($this->client);

        try {
            $service->events->delete('primary', $eventId);
        } catch (\Exception $e) {
            \Log::error('Erro ao deletar evento no Google Calendar: ' . $e->getMessage());
        }
    }

    public function syncFromGoogle(User $user)
    {
        if (!$user->google_access_token || $user->google_sync_mode !== 'bidirectional') return;

        $this->refreshTokenIfNeeded($user);
        $service = new Calendar($this->client);

        $optParams = [
            'updatedMin' => now()->subHours(24)->toRfc3339String(), // Puxa o que mudou nas ultimas 24h
            'showDeleted' => true,
            'singleEvents' => true,
            'maxResults' => 100,
        ];

        try {
            $events = $service->events->listEvents('primary', $optParams);
            
            foreach ($events->getItems() as $event) {
                // Procura a tarefa no nosso banco pelo ID do Google
                $tarefa = TarefaAgenda::where('google_event_id', $event->getId())->first();

                if ($event->getStatus() === 'cancelled') {
                    if ($tarefa) {
                        // Deletada no Google, deleta no CRM (sem acionar o observer de delete)
                        TarefaAgenda::withoutEvents(function () use ($tarefa) {
                            $tarefa->delete();
                        });
                    }
                    continue;
                }

                $data = [
                    'titulo' => $event->getSummary() ?? 'Sem título',
                    'descricao' => $event->getDescription(),
                    'data_inicio' => $event->getStart()->getDateTime() ?? $event->getStart()->getDate(),
                    'data_fim' => $event->getEnd()->getDateTime() ?? $event->getEnd()->getDate(),
                    'google_event_id' => $event->getId(),
                    'status' => 'Pendente',
                    'dia_inteiro' => empty($event->getStart()->getDateTime()),
                ];

                TarefaAgenda::withoutEvents(function () use ($tarefa, $data) {
                    if ($tarefa) {
                        $tarefa->update($data);
                    } else {
                        TarefaAgenda::create($data);
                    }
                });
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar eventos do Google Calendar: ' . $e->getMessage());
        }
    }
}
