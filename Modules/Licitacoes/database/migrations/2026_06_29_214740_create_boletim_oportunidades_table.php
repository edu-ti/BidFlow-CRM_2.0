<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletim_oportunidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boletim_id')->constrained('boletins')->cascadeOnDelete();
            $table->foreignId('oportunidade_licitacao_id')->constrained('oportunidades_licitacoes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletim_oportunidade');
    }
};
