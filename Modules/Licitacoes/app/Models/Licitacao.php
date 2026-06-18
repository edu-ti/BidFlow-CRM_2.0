<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Licitacao extends Model
{
    use HasFactory;

    protected $table = 'licitacoes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'numero_edital',
        'numero_processo',
        'modalidade',
        'local_disputa',
        'uasg',
        'orgao_cnpj',
        'orgao_razao_social',
        'orgao_nome_fantasia',
        'orgao_endereco',
        'orgao_bairro',
        'orgao_cidade',
        'orgao_estado',
        'orgao_cep',
        'objeto',
        'data_disputa',
        'hora_disputa',
        'status',
    ];

    protected $casts = [
        'data_disputa' => 'date',
    ];
}
