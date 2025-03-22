<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('items', ItemsController::class)->middleware(['auth', 'verified']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
