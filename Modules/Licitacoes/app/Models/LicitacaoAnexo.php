<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Licitacoes\Database\Factories\LicitacaoAnexoFactory;

class LicitacaoAnexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'licitacao_id',
        'tipo',
        'nome',
        'arquivo_path',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class, 'licitacao_id');
    }

    // protected static function newFactory(): LicitacaoAnexoFactory
    // {
    //     // return LicitacaoAnexoFactory::new();
    // }
}
