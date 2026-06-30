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
        Schema::create('consignado_movimentacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimentacao_id')->constrained('consignado_movimentacoes')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->integer('quantidade');
            $table->string('lote')->nullable();
            $table->date('validade')->nullable();
            $table->boolean('faturado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consignado_movimentacao_itens');
    }
};
