<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OportunidadeProduto extends Pivot
{
    protected $table = 'oportunidade_produto';

    protected $guarded = [];

    public function oportunidade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Oportunidade::class, 'oportunidade_id');
    }

    public function produto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    protected static function booted()
    {
        static::saved(function ($pivot) {
            if ($pivot->oportunidade) {
                $pivot->oportunidade->atualizarValorEstimado();
            }
        });

        static::deleted(function ($pivot) {
            if ($pivot->oportunidade) {
                $pivot->oportunidade->atualizarValorEstimado();
            }
        });
    }
}
