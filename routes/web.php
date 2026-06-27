<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GoogleCalendarController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/google/redirect', [GoogleCalendarController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleCalendarController::class, 'callback'])->name('google.callback');
    Route::post('/google/disconnect', [GoogleCalendarController::class, 'disconnect'])->name('google.disconnect');
});
