<?php

use App\Http\Controllers\Api\AnggotaApiController;
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AnggotaApiController::class, 'profile']);
    Route::get('/simpanan', [AnggotaApiController::class, 'simpanan']);
    Route::get('/pinjaman', [AnggotaApiController::class, 'pinjaman']);
});
