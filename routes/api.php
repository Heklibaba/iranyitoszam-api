<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/counties', [CountyController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/counties', [CountyController::class, 'store'])
    ->middleware('auth:sanctum');
Route::post('/cities', [CityController::class, 'store'])
    ->middleware('auth:sanctum');
Route::put('/cities/{city}', [CityController::class, 'update'])
    ->middleware('auth:sanctum');
Route::delete('/cities/{city}', [CityController::class, 'destroy'])
    ->middleware('auth:sanctum');
Route::put('/counties/{county}', [CountyController::class, 'update'])
    ->middleware('auth:sanctum');
Route::delete('/counties/{county}', [CountyController::class, 'destroy'])
    ->middleware('auth:sanctum');
Route::get('/counties/{county}', [CountyController::class, 'show']);
Route::get('/cities/{city}', [CityController::class, 'show']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');