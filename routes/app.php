<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('patients', PatientController::class);
    Route::resource('sells', SellController::class);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
