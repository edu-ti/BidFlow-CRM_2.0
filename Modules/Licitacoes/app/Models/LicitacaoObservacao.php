<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Licitacoes\Database\Factories\LicitacaoObservacaoFactory;

class LicitacaoObservacao extends Model
{
    use HasFactory;

    protected $table = 'licitacao_observacoes';

    protected $fillable = [
        'licitacao_id',
        'user_id',
        'texto',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class, 'licitacao_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // protected static function newFactory(): LicitacaoObservacaoFactory
    // {
    //     // return LicitacaoObservacaoFactory::new();
    // }
}
