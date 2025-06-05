<?php

use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('status', StatusController::class);
Route::put('/status/restore/{id}', [StatusController::class, 'restore']);
