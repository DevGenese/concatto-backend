<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleUserController;
use App\Http\Controllers\ExpenseController;

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/cooperatives', CooperativeController::class);
    Route::apiResource('/locations', LocationController::class);
    Route::apiResource('/localities', LocalityController::class);
    Route::apiResource('/expense-types', ExpenseTypeController::class);
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/{id}', [ScheduleController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::apiResource('/schedule-users', ScheduleUserController::class);
    Route::apiResource('/expenses', ExpenseController::class);
});

