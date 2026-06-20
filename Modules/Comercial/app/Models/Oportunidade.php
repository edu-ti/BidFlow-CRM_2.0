<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Comercial\Database\Factories\OportunidadeFactory;

class Oportunidade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        //return OportunidadeFactory::new();
    }

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Fornecedores\Models\Fornecedor::class, 'fornecedor_id');
    }

    public function propostas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PropostaComercial::class, 'oportunidade_id');
    }

    public function tarefas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TarefaAgenda::class, 'oportunidade_id');
    }

    public function historicos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Historico::class, 'historicoable');
    }
}
