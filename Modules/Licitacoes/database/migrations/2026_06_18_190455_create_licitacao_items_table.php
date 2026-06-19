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
        Schema::create('licitacao_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacao_id')->constrained('licitacoes')->onDelete('cascade');
            $table->string('numero_item')->nullable();
            $table->string('descricao');
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->string('marca')->nullable();
            $table->decimal('valor_unitario', 15, 2)->nullable();
            $table->integer('quantidade')->default(1);
            $table->decimal('valor_total', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licitacao_items');
    }
};
