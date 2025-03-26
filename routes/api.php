<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Conteudo\ModuloController;
use App\Http\Controllers\Conteudo\LivroController;
use App\Http\Controllers\Auth\SocialMediaController;
use App\Http\Controllers\User\UserAdminController;
use App\Http\Controllers\Log\LogController;


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
        Route::post('login/moodle', [SocialMediaController::class, 'loginMoodle'])->name('login.moodle');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        Route::post('refresh_device', [AuthController::class, 'refresh_device'])->name('refresh.device');
        Route::middleware('auth:api')->group(function () {
            Route::post('me', [AuthController::class, 'me'])->name('me');
            Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });


    Route::apiResource('users', UserController::class)
        ->middleware('auth:api')
        ->except('store');

    Route::put('admin/atualizar-senha', [UserAdminController::class, 'updatePrimeiroAcesso'])
            ->name('update-primeiro-acesso');
});

Route::middleware('auth:api')->group(function () {

    Route::group([
        'prefix' => '/admin',
        'as' => 'admin.'
    ], function () {
        Route::post('criar-senha', [UserAdminController::class, 'register'])
            ->middleware('auth:api')
            ->name('register');
        // Route::put('atualizar-senha', [UserAdminController::class, 'updatePrimeiroAcesso'])
        //     ->name('update-primeiro-acesso');
    });

    Route::group([
        'prefix' => 'conteudos',
        'as' => 'conteudos.',
    ], function () {
        Route::apiResource('modulo', ModuloController::class)->parameters(['modulo' => 'modulo']);
        Route::apiResource('livro', LivroController::class)->parameters(['livro' => 'livro']);

    });

    Route::group([
        'prefix' => 'logs',
        'as' => 'logs.',
    ], function () {
        Route::get('livros', [LogController::class, 'index']);
        Route::get('acessos', [LogController::class, 'indexAcesso']);
        Route::get('show/{log}', [LogController::class, 'show']);

        Route::get('grafico/registro_acesso', [LogController::class, 'registroAcessoChart']);
        Route::get('grafico/registro_mes_acesso', [LogController::class, 'registroAcessoMesChart']);

        Route::get('grafico/registro_livro', [LogController::class, 'registroLivroChart']);
        Route::get('grafico/registro_mes_livro', [LogController::class, 'registroLivroMesChart']);
    });

    Route::post('users/suporte', [UserController::class, 'sendSuport'])
        ->name('users.suporte');

    Route::get('users/reenviar_token/{user}', [UserController::class, 'sendToken'])
        ->name('users.reenviar');

    Route::get('lista_modulo', [ModuloController::class, 'lista']);

    Route::get('tags/{livro}', [LivroController::class, 'listTags']);

    Route::get('dashboard/total', [LivroController::class, 'total']);

    Route::get('registrar_download/{livro}', [LivroController::class, 'registarDownload']);
});
