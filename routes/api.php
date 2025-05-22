<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// Свободный доступ
Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/posts', [PostController::class, 'indexAll']);

    //Роуты доступные только при авторизации.
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logOut']);
        Route::post('/user/info', [UserController::class, 'show'])->name('user.info');
        Route::patch('/user/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/delete', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/posts/user', [PostController::class, 'index'])->name('post.user');
        Route::post('/post/create', [PostController::class, 'store'])->name('post.create');
        Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
        Route::patch('/post/{post}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    });

