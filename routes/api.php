<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Rutas públicas
Route::post('/registrar-usuario', [UserController::class, 'register']);
Route::post('/iniciar-sesion', [UserController::class, 'login']);

// Rutas protegidas (requiere autorización con tokens)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/listar-perfiles', [PerfilController::class, 'index']);

