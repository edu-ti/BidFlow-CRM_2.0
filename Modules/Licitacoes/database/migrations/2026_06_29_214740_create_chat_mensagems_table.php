<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licitacao_id')->constrained('licitacoes')->cascadeOnDelete();
            $table->string('tipo');
            $table->text('texto');
            $table->dateTime('data_hora');
            $table->boolean('is_alert')->default(false);
            $table->string('keyword_encontrada')->nullable();
            $table->boolean('lida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_mensagens');
    }
};
