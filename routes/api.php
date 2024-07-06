<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScheduleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ScheduleController::class)->group(function () {
    Route::get('/schedules', 'index');
    Route::post('/schedules', 'store');
    Route::put('/schedules/{schedule}', 'update');
    Route::delete('/schedules/{schedule}', 'destroy');
});
