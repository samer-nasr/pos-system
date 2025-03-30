<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('items', ItemsController::class);
    Route::get('category/export' , [CategoryController::class , 'export'])->name('category.export');
    Route::resource('category', CategoryController::class);
    Route::resource('brands', BrandController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
