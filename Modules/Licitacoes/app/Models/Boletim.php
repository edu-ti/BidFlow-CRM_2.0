<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boletim extends Model
{
    use HasFactory;

    protected $table = 'boletins';

    protected $fillable = [
        'titulo',
        'numero_edicao',
        'data_geracao',
    ];

    protected $casts = [
        'data_geracao' => 'datetime',
    ];

    public function oportunidades()
    {
        return $this->belongsToMany(OportunidadeLicitacao::class, 'boletim_oportunidade', 'boletim_id', 'oportunidade_licitacao_id');
    }
}
