<?php

namespace Modules\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatConfiguracao extends Model
{
    use HasFactory;

    protected $table = 'chat_configuracoes';

    protected $fillable = [
        'palavras_chave',
        'notificar_email',
        'notificar_sonoro',
        'notificar_push',
        'tipo_mensagem_alerta',
    ];

    protected $casts = [
        'palavras_chave' => 'array',
        'notificar_email' => 'boolean',
        'notificar_sonoro' => 'boolean',
        'notificar_push' => 'boolean',
    ];
}
