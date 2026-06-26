<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->decimal('fixo', 15, 2)->nullable()->after('valor');
            $table->decimal('comissao_percentual', 5, 2)->nullable()->after('fixo');
            $table->boolean('ativo')->default(true)->after('comissao_percentual');
        });
    }

    public function down(): void
    {
        Schema::table('metas', function (Blueprint $table) {
            $table->dropColumn(['fixo', 'comissao_percentual', 'ativo']);
        });
    }
};
