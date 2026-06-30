<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMensagem extends Model
{
    use HasFactory;

    protected $table = 'chat_mensagens';

    protected $fillable = [
        'licitacao_id',
        'tipo',
        'texto',
        'data_hora',
        'is_alert',
        'keyword_encontrada',
        'lida',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'is_alert' => 'boolean',
        'lida' => 'boolean',
    ];

    public function licitacao()
    {
        return $this->belongsTo(Licitacao::class);
    }
}
