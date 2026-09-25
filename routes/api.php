<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

// Same contract as the legacy api/index.php, so the existing frontend JS is unchanged.
Route::get('/alerts', [AlertController::class, 'index']);
Route::post('/alerts', [AlertController::class, 'store']);
Route::put('/alerts/{id}', [AlertController::class, 'update']);
Route::post('/alerts/{id}/respond', [AlertController::class, 'respond']);

Route::get('/facilities', [FacilityController::class, 'index']);
Route::get('/stats', StatsController::class);
