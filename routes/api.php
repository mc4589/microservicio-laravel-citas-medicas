<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Rutas públicas
Route::post('/registrar-usuario', [UserController::class, 'register']);
Route::post('/iniciar-sesion', [UserController::class, 'login']);

// Rutas protegidas (requiere autorización con tokens)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/listar-usuarios', [UserController::class, 'index']);
    Route::get('/usuario/{id}', [UserController::class, 'show']);
    Route::put('/actualizar-usuario/{id}', [UserController::class, 'update']);
    Route::delete('/eliminar-usuario/{id}', [UserController::class, 'destroy']);
});







