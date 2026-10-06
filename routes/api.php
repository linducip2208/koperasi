<?php

use App\Http\Controllers\Api\AnggotaApiController;
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

/*
 * API anggota (mobile / portal) — versi v1 di /api/v1/*.
 * Alias lama /api/* dipertahankan untuk kompatibilitas klien existing.
 */
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/me', [AnggotaApiController::class, 'profile']);
        Route::get('/simpanan', [AnggotaApiController::class, 'simpanan']);
        Route::get('/pinjaman', [AnggotaApiController::class, 'pinjaman']);
        Route::get('/reports', [\App\Http\Controllers\Api\ReportApiController::class, 'index']);
        Route::get('/reports/{key}', [\App\Http\Controllers\Api\ReportApiController::class, 'show']);
        Route::post('/reports/{key}/run', [\App\Http\Controllers\Api\ReportApiController::class, 'run']);
        Route::post('/reports/{key}/export', [\App\Http\Controllers\Api\ReportApiController::class, 'export']);
    });
});

// Alias legacy (tanpa versi) — delegasi ke controller yang sama.
Route::post('/login', [AuthApiController::class, 'login'])->middleware('throttle:5,1');
Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AnggotaApiController::class, 'profile']);
    Route::get('/simpanan', [AnggotaApiController::class, 'simpanan']);
    Route::get('/pinjaman', [AnggotaApiController::class, 'pinjaman']);
    Route::get('/reports', [\App\Http\Controllers\Api\ReportApiController::class, 'index']);
    Route::get('/reports/{key}', [\App\Http\Controllers\Api\ReportApiController::class, 'show']);
    Route::post('/reports/{key}/run', [\App\Http\Controllers\Api\ReportApiController::class, 'run']);
    Route::post('/reports/{key}/export', [\App\Http\Controllers\Api\ReportApiController::class, 'export']);
});
