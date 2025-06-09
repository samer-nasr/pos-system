<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use League\CommonMark\Extension\SmartPunct\DashParser;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    // Route::resource('dashboard', DashboardController::class);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('dashboard/pay', [DashboardController::class, 'pay'])->name('dashboard.pay');
    Route::get('items/export' , [ItemsController::class , 'export'])->name('items.export');

    Route::resource('users', UserController::class);

    Route::resource('items', ItemsController::class);
    Route::get('category/export' , [CategoryController::class , 'export'])->name('category.export');
    Route::post('category/import' , [CategoryController::class , 'import'])->name('category.import');
    Route::resource('category', CategoryController::class);
    Route::get('brands/export' , [BrandController::class , 'export'])->name('brands.export');
    Route::post('brands/import' , [BrandController::class , 'import'])->name('brands.import');
    Route::resource('brands', BrandController::class);
    Route::resource('rates' , RateController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
