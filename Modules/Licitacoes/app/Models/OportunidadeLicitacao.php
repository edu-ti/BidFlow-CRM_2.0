<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OportunidadeLicitacao extends Model
{
    use HasFactory;

    protected $table = 'oportunidades_licitacoes';

    protected $fillable = [
        'orgao',
        'objeto',
        'edital',
        'estado',
        'cidade',
        'modalidade',
        'data_publicacao',
        'data_abertura',
        'valor_estimado',
        'uasg',
        'processo',
        'conlicitacao',
        'link_detalhes',
        'portal_origem',
        'status_badge',
        'status_cor',
        'favorito',
        'visualizacoes',
        'gerenciada',
    ];

    protected $casts = [
        'data_publicacao' => 'datetime',
        'data_abertura' => 'datetime',
        'valor_estimado' => 'decimal:2',
        'favorito' => 'boolean',
        'gerenciada' => 'boolean',
    ];

    public function boletins()
    {
        return $this->belongsToMany(Boletim::class, 'boletim_oportunidade', 'oportunidade_licitacao_id', 'boletim_id');
    }
}
