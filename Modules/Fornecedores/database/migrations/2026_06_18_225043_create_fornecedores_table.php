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
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_pessoa', ['PF', 'PJ'])->default('PJ');
            $table->string('cpf_cnpj')->unique();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            
            // Dados de contato básicos
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            
            // Endereço
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            
            // Contato Específico
            $table->string('contato_nome')->nullable();
            $table->string('contato_cargo')->nullable();
            $table->string('contato_setor')->nullable();
            $table->string('contato_email')->nullable();
            $table->string('contato_telefone')->nullable();
            
            // Classificação e Status
            $table->enum('classificacao', ['Fornecedor', 'Cliente', 'Concorrente'])->default('Fornecedor');
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
