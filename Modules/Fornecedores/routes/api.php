<?php

use Illuminate\Support\Facades\Route;
use Modules\Fornecedores\Http\Controllers\FornecedoresController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('fornecedores', FornecedoresController::class)->names('fornecedores');
});
