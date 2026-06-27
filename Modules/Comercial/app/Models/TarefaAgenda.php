<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Comercial\Database\Factories\TarefaAgendaFactory;

class TarefaAgenda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        //return TarefaAgendaFactory::new();
    }

    protected static function booted()
    {
        static::saved(function ($tarefa) {
            $user = auth()->user() ?? \App\Models\User::first(); // Fallback in case of console
            if ($user && $user->google_access_token && in_array($user->google_sync_mode, ['unidirectional', 'bidirectional'])) {
                $service = app(\App\Services\GoogleCalendarService::class);
                if ($tarefa->google_event_id) {
                    $service->updateEvent($tarefa, $user);
                } else {
                    $service->createEvent($tarefa, $user);
                }
            }
        });

        static::deleted(function ($tarefa) {
            $user = auth()->user() ?? \App\Models\User::first();
            if ($user && $user->google_access_token && in_array($user->google_sync_mode, ['unidirectional', 'bidirectional']) && $tarefa->google_event_id) {
                $service = app(\App\Services\GoogleCalendarService::class);
                $service->deleteEvent($tarefa->google_event_id, $user);
            }
        });
    }

    public function oportunidade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Oportunidade::class, 'oportunidade_id');
    }

    public function historicos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Historico::class, 'historicoable');
    }
}
