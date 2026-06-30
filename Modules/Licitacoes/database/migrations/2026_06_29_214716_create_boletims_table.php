<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletins', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->integer('numero_edicao');
            $table->dateTime('data_geracao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletins');
    }
};
