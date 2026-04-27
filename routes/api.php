<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentApiController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Routes pour les rendez-vous
    Route::apiResource('appointments', AppointmentApiController::class);
    Route::get('services', [AppointmentApiController::class, 'services']);
});
