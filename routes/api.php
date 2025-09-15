<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//post group
    Route::middleware('token')->group(function(){
        Route::apiResource('/posts', PostController::class);
        Route::apiResource('/images', ImageController::class);
    });

//Auth
    Route::post('/login', [AuthController::class, 'Auth']);

//    Route::post('/login', [AuthController::class, 'login'])->name('login');
//    Route::post('/sign', [AuthController::class, 'register'])->name('register');