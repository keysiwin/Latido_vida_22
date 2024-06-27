<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/persons', [PersonController::class, 'store']);
Route::get('/persons', [PersonController::class, 'index']);
Route::get('/persons/{id}', [PersonController::class, 'show']);
Route::put('/persons', [PersonController::class, 'update']);
Route::delete('/persons/{id}', [PersonController::class, 'destroy']);


// Rutas para locations
Route::post('/persons/{personId}/locations', [LocationController::class, 'store']);
Route::put('/persons/{personId}/locations/{locationId}', [LocationController::class, 'update']);
Route::delete('/persons/{personId}/locations/{locationId}', [LocationController::class, 'destroy']);




