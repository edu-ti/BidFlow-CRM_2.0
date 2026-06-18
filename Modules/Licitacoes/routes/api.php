<?php

use Illuminate\Support\Facades\Route;
use Modules\Licitacoes\Http\Controllers\LicitacoesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('licitacoes', LicitacoesController::class)->names('licitacoes');
});
