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
        Schema::table('proposta_comercials', function (Blueprint $table) {
            $table->string('tipo_frete')->nullable();
            $table->decimal('valor_frete', 10, 2)->nullable();
            $table->json('termos_comerciais')->nullable();
        });

        Schema::table('proposta_comercial_items', function (Blueprint $table) {
            $table->decimal('desconto_percentual', 5, 2)->nullable();
            $table->json('parametros_adicionais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposta_comercials', function (Blueprint $table) {
            $table->dropColumn(['tipo_frete', 'valor_frete', 'termos_comerciais']);
        });

        Schema::table('proposta_comercial_items', function (Blueprint $table) {
            $table->dropColumn(['desconto_percentual', 'parametros_adicionais']);
        });
    }
};
