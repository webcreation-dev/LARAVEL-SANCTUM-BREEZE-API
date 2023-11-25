<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('patients', PatientController::class);
    Route::resource('sells', SellController::class);
    Route::resource('notifications', NotificationController::class);

    Route::get('/get_all_employees', [RegisteredUserController::class, 'getAllEmployees']);
    Route::post('/create_employees', [RegisteredUserController::class, 'createEmployee']);
    Route::post('/update_employees', [RegisteredUserController::class, 'updateEmployee']);

    Route::get('/get_total_patients_by_employee', [RegisteredUserController::class, 'getPatientTreatByEmployee']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

