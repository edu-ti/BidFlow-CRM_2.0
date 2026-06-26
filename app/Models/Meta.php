<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Fornecedores\Models\Fornecedor;

class Meta extends Model
{
    protected $table = 'metas';
    
    protected $fillable = [
        'tipo_entidade',
        'entidade_id',
        'frequencia',
        'mes',
        'ano',
        'estado_uf',
        'valor',
        'fixo',
        'comissao_percentual',
        'ativo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'entidade_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'entidade_id');
    }
}
