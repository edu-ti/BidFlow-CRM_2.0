<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('status')->default('Passagem de Bastão');
            $table->decimal('valor_fechado', 15, 2)->default(0);
            $table->dateTime('data_venda')->nullable();
            $table->text('resumo_venda')->nullable();
            $table->text('anotacoes_cs')->nullable();
            $table->dateTime('data_conclusao_esperada')->nullable();
            $table->dateTime('data_conclusao_real')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboardings');
    }
};
