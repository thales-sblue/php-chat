<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConversationController;


Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/', [ClientController::class, 'store']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::delete('/{id}', [ClientController::class, 'destroy']);
});

Route::prefix('messages')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MessageController::class, 'index']);
    Route::post('/send', [MessageController::class, 'send']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('conversations')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ConversationController::class, 'index']);
    Route::post('/start', [ConversationController::class, 'start']);
    Route::get('/{id}/messages', [ConversationController::class, 'messages']);
});
