<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oportunidades_licitacoes', function (Blueprint $table) {
            $table->id();
            $table->string('orgao')->nullable();
            $table->text('objeto')->nullable();
            $table->string('edital')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cidade')->nullable();
            $table->string('modalidade')->nullable();
            $table->datetime('data_publicacao')->nullable();
            $table->datetime('data_abertura')->nullable();
            $table->decimal('valor_estimado', 15, 2)->nullable();
            $table->string('uasg')->nullable();
            $table->string('processo')->nullable();
            $table->string('conlicitacao')->nullable();
            $table->text('link_detalhes')->nullable();
            $table->string('portal_origem')->nullable();
            $table->string('status_badge')->default('NOVA');
            $table->string('status_cor')->default('green');
            $table->boolean('favorito')->default(false);
            $table->integer('visualizacoes')->default(0);
            $table->boolean('gerenciada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oportunidades_licitacoes');
    }
};
