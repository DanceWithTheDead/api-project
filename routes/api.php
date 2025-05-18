<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
Route::post('/user', [AuthController::class, 'show'])->middleware('auth:sanctum');

Route::post('/post', [PostController::class, 'index']);
Route::post('/post/create', [PostController::class, 'store'])->middleware('auth:sanctum');
