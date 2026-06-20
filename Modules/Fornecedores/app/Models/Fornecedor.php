<?php

namespace Modules\Fornecedores\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = [
        'tipo_pessoa',
        'cpf_cnpj',
        'razao_social',
        'nome_fantasia',
        'email',
        'telefone',
        'celular',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'contato_nome',
        'contato_cargo',
        'contato_setor',
        'contato_email',
        'contato_telefone',
        'classificacao',
        'status',
        'exibir_no_funil_fornecedores',
    ];
}
