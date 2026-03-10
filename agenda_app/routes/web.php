<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', function () {
        return view('app');
    })->name('dashboard');

    Route::prefix('api')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::post('/events', [EventController::class, 'store']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle']);
    });
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
