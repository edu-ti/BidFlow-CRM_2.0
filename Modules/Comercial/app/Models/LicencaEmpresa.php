<?php

namespace Modules\Comercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LicencaEmpresa extends Model
{
    use HasFactory;

    protected $table = 'licencas_empresa';

    protected $fillable = [
        'titulo',
        'data_vencimento',
        'sem_validade',
        'arquivo_path',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'sem_validade' => 'boolean',
    ];
}
