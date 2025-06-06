<?php

use App\Http\Controllers\ArchivedStatusController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);


Route::apiResource('status', StatusController::class);
Route::put('/status/restore/{id}', [StatusController::class, 'restore']);
Route::delete('/status/permanent/delete/{id}', [StatusController::class, 'permanently_delete']);


Route::apiResource('task', TaskController::class);
Route::put('/task/restore/{id}', [TaskController::class, 'restore']);

Route::apiResource('archived-status', ArchivedStatusController::class)->except(['update']);
Route::delete('/archived-status/restore/{id}', [ArchivedStatusController::class, 'restore']);
Route::delete('/archived-status/permanent/delete/{id}', [ArchivedStatusController::class, 'permanently_delete']);
