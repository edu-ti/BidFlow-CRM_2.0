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
        Schema::table('proposta_comercial_items', function (Blueprint $table) {
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->string('tipo')->default('Venda');
            $table->string('imagem')->nullable();
            $table->text('descricao_detalhada')->nullable();
            $table->string('unidade_medida')->nullable()->default('Unidade');
            $table->integer('meses_locacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposta_comercial_items', function (Blueprint $table) {
            $table->dropColumn([
                'fabricante', 
                'modelo', 
                'tipo', 
                'imagem', 
                'descricao_detalhada', 
                'unidade_medida', 
                'meses_locacao'
            ]);
        });
    }
};
