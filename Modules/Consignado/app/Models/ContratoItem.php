<?php

namespace Modules\Consignado\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comercial\Models\Produto;

class ContratoItem extends Model
{
    use HasFactory;

    protected $table = 'consignado_contrato_itens';

    protected $fillable = [
        'contrato_id',
        'produto_id',
        'cota_base',
        'valor_unitario',
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
