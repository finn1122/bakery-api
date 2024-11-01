<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;

Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenido a la API de Bakery',
        'version' => '1.0',
        'documentation' => url('/api/documentation') // Cambia esto si tienes documentación
    ]);
});

Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('user', [JWTAuthController::class, 'getUser']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
});
