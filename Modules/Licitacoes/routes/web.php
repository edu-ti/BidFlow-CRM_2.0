<?php

use Illuminate\Support\Facades\Route;
use Modules\Licitacoes\Http\Controllers\LicitacoesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('licitacoes', LicitacoesController::class)->names('licitacoes');
});
