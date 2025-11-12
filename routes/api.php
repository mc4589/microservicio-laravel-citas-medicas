<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Rutas públicas
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/listar-perfiles', [PerfilController::class, 'index']);

