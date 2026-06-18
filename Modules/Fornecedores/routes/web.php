<?php

use Illuminate\Support\Facades\Route;
use Modules\Fornecedores\Http\Controllers\FornecedoresController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('fornecedores', FornecedoresController::class)->names('fornecedores');
});
