<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('/admin', 'admin');
Route::view('/rescuee', 'rescuee');

// Keep the old static-file URLs working.
Route::view('/admin.html', 'admin');
Route::view('/test-api.html', 'rescuee');
Route::view('/debug.html', 'debug');
