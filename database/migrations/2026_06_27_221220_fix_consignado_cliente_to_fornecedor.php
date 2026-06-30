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
        Schema::table('consignado_contratos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('restrict');
        });

        Schema::table('consignado_saldos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
        });

        Schema::table('consignado_movimentacoes', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('cascade');
        });

        Schema::dropIfExists('clientes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor', function (Blueprint $table) {
            //
        });
    }
};
