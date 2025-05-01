<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        dd('test');
    });

    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
});



