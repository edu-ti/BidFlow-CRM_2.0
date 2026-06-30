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
        Schema::table('licitacao_items', function (Blueprint $table) {
            $table->string('numero_lote')->nullable()->after('licitacao_id');
            $table->decimal('valor_unit_referencia', 15, 2)->nullable()->after('quantidade');
            $table->string('tipo_cota')->nullable()->after('status');

            // Dropping old specific fields that are moving to participants
            $table->dropColumn(['fabricante', 'modelo', 'marca', 'valor_unitario', 'valor_total']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licitacao_items', function (Blueprint $table) {
            $table->dropColumn(['numero_lote', 'valor_unit_referencia', 'tipo_cota']);
            
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->string('marca')->nullable();
            $table->decimal('valor_unitario', 15, 2)->nullable();
            $table->decimal('valor_total', 15, 2)->nullable();
        });
    }
};
