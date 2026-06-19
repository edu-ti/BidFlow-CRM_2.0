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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->text('descricao')->nullable();
            $table->decimal('valor_unitario', 15, 2)->default(0);
            $table->string('unidade', 50)->default('UN');
            $table->string('imagem_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
