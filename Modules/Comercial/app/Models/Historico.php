<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Fornecedores\Models\Fornecedor;

class Historico extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function historicoable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
