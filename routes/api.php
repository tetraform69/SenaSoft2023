<?php

use App\Http\Controllers\ConexionController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function () {
    Route::get('/nodos', [UbicacionController::class, 'read']);
    Route::post('/nodos',[UbicacionController::class, 'create']);

    Route::post('/conexion',[ConexionController::class, 'create']);
    Route::get('/conexion',[ConexionController::class, 'read']);
    
    Route::post('/ruta',[RutaController::class, 'create']);
    Route::get('/ruta',[RutaController::class, 'read']);
});