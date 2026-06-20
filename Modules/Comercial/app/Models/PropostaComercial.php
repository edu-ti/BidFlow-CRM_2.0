<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Comercial\Database\Factories\PropostaComercialFactory;

class PropostaComercial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory()
    {
        //return PropostaComercialFactory::new();
    }

    public function fornecedor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Fornecedores\Models\Fornecedor::class, 'fornecedor_id');
    }

    public function itens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PropostaComercialItem::class, 'proposta_comercial_id');
    }

    public function oportunidade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Oportunidade::class, 'oportunidade_id');
    }
}
