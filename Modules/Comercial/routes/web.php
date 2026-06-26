<?php

use Illuminate\Support\Facades\Route;
use Modules\Comercial\Http\Controllers\ComercialController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('comercials', ComercialController::class)->names('comercial');
    Route::get('propostas/{record}/imprimir', [\Modules\Comercial\Http\Controllers\PropostaPrintController::class, 'imprimir'])->name('propostas.imprimir');
});
