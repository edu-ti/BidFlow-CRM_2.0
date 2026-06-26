<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Comercial\Database\Factories\PropostaComercialItemFactory;

class PropostaComercialItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        //return PropostaComercialItemFactory::new();
    }

    protected $casts = [
        'parametros_adicionais' => 'array',
    ];

    public function produto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function proposta(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PropostaComercial::class, 'proposta_comercial_id');
    }

    protected static function booted()
    {
        static::saved(function ($item) {
            if ($item->proposta) {
                $item->proposta->atualizarValorTotal();
            }
        });

        static::deleted(function ($item) {
            if ($item->proposta) {
                $item->proposta->atualizarValorTotal();
            }
        });
    }
}
