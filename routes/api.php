<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        dd('test');
    });

    // Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    // Route::get('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);
    // Route::post('/category', [\App\Http\Controllers\Api\CategoryController::class, 'store']);
    // Route::put('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'update']);
    // Route::delete('/category/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'destroy']);

    Route::get('/category/items', [\App\Http\Controllers\Api\CategoryController::class, 'category_items']);
    Route::resource('categories' , \App\Http\Controllers\Api\CategoryController::class);
    
    Route::get('item/category' , [\App\Http\Controllers\Api\ItemController::class, 'item_category']);
    Route::resource('items' , \App\Http\Controllers\Api\ItemController::class);
});



