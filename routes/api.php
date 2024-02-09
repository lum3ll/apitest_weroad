<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TravelController;

// Public and accessible route to all users
Route::get('/tours/{slug}', [TourController::class, 'index']);
Route::post('/login', [MainController::class, 'login']);

// Private and accessible route to admin users
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/travels/{slug}/tours', [TourController::class, 'store'])->middleware(['role:admin']);
    Route::post('/travels', [TravelController::class, 'store'])->middleware(['role:admin']);
    Route::post('/logout', [MainController::class, 'logout'])->middleware(['role:admin', 'role:editor']);
});
