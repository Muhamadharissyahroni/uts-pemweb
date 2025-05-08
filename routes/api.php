<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/users', [AuthController::class, 'index']);
Route::delete('/users/{id}', [AuthController::class, 'destroy']);

Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/products', ProductController::class);

