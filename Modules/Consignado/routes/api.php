<?php

use Illuminate\Support\Facades\Route;
use Modules\Consignado\Http\Controllers\ConsignadoController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('consignados', ConsignadoController::class)->names('consignado');
});
