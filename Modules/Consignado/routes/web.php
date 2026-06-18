<?php

use Illuminate\Support\Facades\Route;
use Modules\Consignado\Http\Controllers\ConsignadoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('consignados', ConsignadoController::class)->names('consignado');
});
