<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerfilBusca extends Model
{
    use HasFactory;

    protected $table = 'perfis_busca';

    protected $fillable = [
        'nome',
        'estados',
        'palavras_chave',
        'modalidades',
        'ativo',
    ];

    protected $casts = [
        'estados' => 'array',
        'palavras_chave' => 'array',
        'modalidades' => 'array',
        'ativo' => 'boolean',
    ];
}
