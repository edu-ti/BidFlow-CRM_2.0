<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoletimOportunidade extends Model
{
    use HasFactory;

    protected $table = 'boletim_oportunidade';

    protected $fillable = [
        'boletim_id',
        'oportunidade_licitacao_id',
    ];
}
