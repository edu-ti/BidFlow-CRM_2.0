<?php

namespace Modules\Consignado\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comercial\Models\Produto;

class MovimentacaoItem extends Model
{
    use HasFactory;

    protected $table = 'consignado_movimentacao_itens';

    protected $fillable = [
        'movimentacao_id',
        'produto_id',
        'quantidade',
        'lote',
        'validade',
        'faturado',
    ];

    protected $casts = [
        'validade' => 'date',
        'faturado' => 'boolean',
    ];

    public function movimentacao()
    {
        return $this->belongsTo(Movimentacao::class, 'movimentacao_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    protected static function booted()
    {
        // Update Saldo when an item is added
        static::created(function ($item) {
            $movimentacao = $item->movimentacao;

            $saldo = Saldo::firstOrCreate(
                [
                    'fornecedor_id' => $movimentacao->fornecedor_id,
                    'produto_id' => $item->produto_id,
                    'lote' => $item->lote,
                ],
                [
                    'validade' => $item->validade,
                    'quantidade' => 0,
                ]
            );

            if ($movimentacao->tipo === 'Remessa') {
                $saldo->quantidade += $item->quantidade;
            } elseif ($movimentacao->tipo === 'Consumo' || $movimentacao->tipo === 'Devolução') {
                $saldo->quantidade -= $item->quantidade;
            }

            $saldo->save();
        });
    }
}
