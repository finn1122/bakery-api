<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Bakery\BakeryController;
use App\Http\Controllers\Api\V1\Branch\BranchController;


Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenido a la API de Bakery',
        'version' => '1.0',
        'documentation' => url('/api/documentation') // Cambia esto si tienes documentación
    ]);
});

//Route::post('register', [RegisterController::class, 'register']);


Route::prefix('v1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    // Rutas públicas que no requieren autenticación
    Route::post('login', [JWTAuthController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [RegisterController::class, 'resendVerification'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // Grupo de rutas que requieren autenticación
    Route::middleware([JwtMiddleware::class])->group(function () {

        // [User]
        Route::prefix('user/{user_id}')->group(function () {
            Route::get('bakery', [BakeryController::class, 'getBakery']);
            Route::post('bakery', [BakeryController::class, 'createBakeryByUserId']);
        });

        // [Bakery]
        Route::prefix('bakery/{bakery_id}')->group(function () {
            Route::post('branch', [BranchController::class, 'addBranchByBakeryId']);
        });

        Route::prefix('bakery/{bakery_id}')->group(function () {
            Route::get('branches', [BranchController::class, 'getAllBranchesByBakeryId']);
        });
    });
});
