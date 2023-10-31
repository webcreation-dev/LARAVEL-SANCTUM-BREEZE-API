<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::resource('patients', PatientController::class);
    Route::resource('sells', SellController::class);
});
