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

    public function oportunidade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Oportunidade::class, 'oportunidade_id');
    }

    public function historicos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Historico::class, 'historicoable');
    }
}
