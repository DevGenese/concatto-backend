<?php

use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\SelectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleUserController;
use App\Http\Controllers\ExpenseController;

Route::post('/login', [AuthenticatedTokenController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticatedTokenController::class, 'logout']);
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/cooperatives', CooperativeController::class);
    Route::apiResource('/locations', LocationController::class);
    Route::apiResource('/localities', LocalityController::class);
    Route::apiResource('/expense-types', ExpenseTypeController::class);
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules-all', [ScheduleController::class, 'allSchedules']);
    Route::get('/schedules/{id}', [ScheduleController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::get('/edit-schedules/{id}', [ScheduleController::class, 'edit']);
    Route::post('/update-schedules', [ScheduleController::class, 'update']);
    Route::get('/schedule-report-csv/{yaer}/{month}', [ScheduleController::class, 'generateCSVReport']);
    Route::get('/schedule-expenses/{id}', [ScheduleController::class, 'scheduleExpenses']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'finalizationSchedule'])
        ->where('id', '[0-9]+');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])
        ->where('id', '[0-9]+');
    Route::apiResource('/schedule-users', ScheduleUserController::class);
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::post('/update-expenses', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);
    Route::prefix('/select')->group(function () {
        Route::get('/expense-types', [SelectController::class, 'expenseTypes']);
        Route::get('/cooperatives', [SelectController::class, 'cooperatives']);
        Route::get('/states', [SelectController::class, 'states']);
        Route::get('/cities/{state}', [SelectController::class, 'cities']);
        Route::get('/location', [SelectController::class, 'location']);
        Route::get('/persons', [SelectController::class, 'persons']);
    });
});