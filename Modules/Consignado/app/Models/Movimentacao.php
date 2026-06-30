<?php

namespace Modules\Consignado\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Clientes\Models\Cliente;
use Modules\Comercial\Models\Produto;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'consignado_movimentacoes';

    protected $fillable = [
        'fornecedor_id',
        'tipo',
        'data_movimento',
        'observacao',
    ];

    protected $casts = [
        'data_movimento' => 'date',
    ];

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Fornecedores\Models\Fornecedor::class, 'fornecedor_id');
    }

    // removed old cliente function

    public function itens()
    {
        return $this->hasMany(MovimentacaoItem::class, 'movimentacao_id');
    }
}
