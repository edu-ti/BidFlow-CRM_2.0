<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oportunidades', function (Blueprint $table) {
            $table->string('pessoa_contato_nome')->nullable();
            $table->string('pessoa_contato_telefone')->nullable();
            $table->string('pessoa_contato_email')->nullable();
            $table->string('funil_selecionado')->default('Funil de vendas')->nullable();
            $table->json('etiquetas')->nullable();
            $table->integer('probabilidade')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('canal_origem')->nullable();
            $table->string('id_canal_origem')->nullable();
            $table->string('visibilidade')->default('Todos os usuários')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('oportunidades', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'pessoa_contato_nome',
                'pessoa_contato_telefone',
                'pessoa_contato_email',
                'funil_selecionado',
                'etiquetas',
                'probabilidade',
                'user_id',
                'canal_origem',
                'id_canal_origem',
                'visibilidade'
            ]);
        });
    }
};
