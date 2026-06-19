<?php

use Illuminate\Support\Facades\Route;
use Modules\Comercial\Http\Controllers\ComercialController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('comercials', ComercialController::class)->names('comercial');
});
