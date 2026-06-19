<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Licitacoes\Database\Factories\LicitacaoItemFactory;

class LicitacaoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'licitacao_id',
        'numero_item',
        'descricao',
        'fabricante',
        'modelo',
        'marca',
        'valor_unitario',
        'quantidade',
        'valor_total',
        'status',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class, 'licitacao_id');
    }

    // protected static function newFactory(): LicitacaoItemFactory
    // {
    //     // return LicitacaoItemFactory::new();
    // }
}
