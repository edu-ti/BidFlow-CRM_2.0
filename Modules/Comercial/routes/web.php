<?php

use Illuminate\Support\Facades\Route;
use Modules\Comercial\Http\Controllers\ComercialController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('comercials', ComercialController::class)->names('comercial');
});
