<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Conteudo\ModuloController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    Route::group([
        'prefix' => 'auth',
        'as' => 'auth.',
        'middleware' => ['api']
    ], function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        Route::post('refresh_device', [AuthController::class, 'refresh_device'])->name('refresh.device');
        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });


    Route::apiResource('users', UserController::class)
        ->middleware('auth:api')
        ->except('store');
});

Route::middleware('auth:api')->group(function () {

    Route::group([
        'prefix' => 'conteudos',
        'as' => 'conteudos.',
    ], function () {
        Route::apiResource('modulo', ModuloController::class)->parameters(['modulo' => 'modulo']);
        
    });

    
});