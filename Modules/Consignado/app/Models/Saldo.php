<?php

namespace Modules\Consignado\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Clientes\Models\Cliente;
use Modules\Comercial\Models\Produto;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'consignado_saldos';

    protected $fillable = [
        'fornecedor_id',
        'produto_id',
        'lote',
        'validade',
        'quantidade',
    ];

    protected $casts = [
        'validade' => 'date',
    ];

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Fornecedores\Models\Fornecedor::class, 'fornecedor_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
