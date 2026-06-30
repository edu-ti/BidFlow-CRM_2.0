<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Fornecedores\Models\Fornecedor;

class LicitacaoItemParticipante extends Model
{
    protected $fillable = [
        'licitacao_item_id',
        'fornecedor_id',
        'fabricante_marca',
        'modelo',
        'valor_unitario',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(LicitacaoItem::class, 'licitacao_item_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
