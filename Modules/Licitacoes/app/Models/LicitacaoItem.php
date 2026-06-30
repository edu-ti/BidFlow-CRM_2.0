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
        'numero_lote',
        'numero_item',
        'descricao',
        'quantidade',
        'valor_unit_referencia',
        'status',
        'tipo_cota',
    ];

    public function participantes()
    {
        return $this->hasMany(LicitacaoItemParticipante::class, 'licitacao_item_id');
    }

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class, 'licitacao_id');
    }

    // protected static function newFactory(): LicitacaoItemFactory
    // {
    //     // return LicitacaoItemFactory::new();
    // }
}
