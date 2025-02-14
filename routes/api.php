<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleUserController;
use App\Http\Controllers\ExpenseController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('cooperatives', CooperativeController::class);
    Route::apiResource('locations', LocationController::class);
    Route::apiResource('localities', LocalityController::class);
    Route::apiResource('expense-types', ExpenseTypeController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('schedule-users', ScheduleUserController::class);
    Route::apiResource('expenses', ExpenseController::class);
});
