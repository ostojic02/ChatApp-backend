<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\UserController;   
use App\Http\Controllers\UpdatePasswordController;

Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::apiResource('user', UserController::class)->only(['index']);
});

Route::get('/messages/search', [ChatMessageController::class, 'search']);

Route::middleware('auth:sanctum')->post('/change/password', [UpdatePasswordController::class, 'update']);

Route::fallback(function () {return response()->json(['message' => 'Route not found'], 404);});