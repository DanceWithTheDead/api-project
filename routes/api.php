<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Свободный доступ
Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/posts', [PostController::class, 'indexAll']);

    //Роуты доступные только при авторизации.
    Route::post('/logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
    Route::post('/user', [UserController::class, 'index'])->name('user.info')->middleware('auth:sanctum');

    Route::post('/post', [PostController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/post/create', [PostController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/post/{post}', [PostController::class, 'show'])->middleware('auth:sanctum');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware('auth:sanctum');
    Route::patch('/post/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');
