<?php

namespace Modules\Consignado\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Clientes\Models\Cliente;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'consignado_contratos';

    protected $fillable = [
        'fornecedor_id',
        'numero_contrato',
        'processo_pregao',
        'data_inicio',
        'data_fim',
        'status',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Fornecedores\Models\Fornecedor::class, 'fornecedor_id');
    }

    // removed old cliente function

    public function itens()
    {
        return $this->hasMany(ContratoItem::class, 'contrato_id');
    }
}
