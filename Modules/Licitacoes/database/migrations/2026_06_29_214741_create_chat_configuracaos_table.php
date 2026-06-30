<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->json('palavras_chave')->nullable();
            $table->boolean('notificar_email')->default(false);
            $table->boolean('notificar_sonoro')->default(true);
            $table->boolean('notificar_push')->default(false);
            $table->string('tipo_mensagem_alerta')->default('todas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_configuracoes');
    }
};
