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
            $table->foreignId('oportunidade_id')->nullable()->constrained('oportunidades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposta_comercials', function (Blueprint $table) {
            $table->dropForeign(['oportunidade_id']);
            $table->dropColumn('oportunidade_id');
        });
    }
};
