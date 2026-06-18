<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licitacoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_edital');
            $table->string('numero_processo')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('local_disputa')->nullable();
            $table->string('uasg')->nullable();
            
            $table->string('orgao_cnpj')->nullable();
            $table->string('orgao_razao_social')->nullable();
            $table->string('orgao_nome_fantasia')->nullable();
            $table->string('orgao_endereco')->nullable();
            $table->string('orgao_bairro')->nullable();
            $table->string('orgao_cidade')->nullable();
            $table->string('orgao_estado')->nullable();
            $table->string('orgao_cep')->nullable();
            
            $table->text('objeto')->nullable();
            $table->date('data_disputa')->nullable();
            $table->time('hora_disputa')->nullable();
            $table->string('status')->default('Em análise');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacoes');
    }
};
