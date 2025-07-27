<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CategoryController;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

  
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

   
    Route::middleware('role:admin|author')->group(function () {
        Route::apiResource('articles', ArticleController::class);
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
    });
});


