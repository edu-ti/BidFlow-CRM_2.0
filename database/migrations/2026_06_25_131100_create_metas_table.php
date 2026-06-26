<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_entidade')->index(); // 'global', 'user', 'fornecedor'
            $table->unsignedBigInteger('entidade_id')->nullable()->index();
            $table->string('frequencia')->default('mensal'); // 'mensal', 'anual'
            $table->integer('mes')->nullable();
            $table->integer('ano')->nullable();
            $table->string('estado_uf', 2)->nullable();
            $table->decimal('valor', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};
