<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);


Route::apiResource('status', StatusController::class);
Route::put('/status/restore/{id}', [StatusController::class, 'restore']);


Route::apiResource('task', TaskController::class);
Route::put('/task/restore/{id}', [TaskController::class, 'restore']);
