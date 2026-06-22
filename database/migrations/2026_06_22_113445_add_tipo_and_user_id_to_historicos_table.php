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
        Schema::table('historicos', function (Blueprint $table) {
            $table->string('tipo')->default('anotacao')->after('historicoable_type');
            $table->foreignId('user_id')->nullable()->after('fornecedor_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historicos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['tipo', 'user_id']);
        });
    }
};
