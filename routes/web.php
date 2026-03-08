<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\DashboardController;




Route::resource('products', ProductController::class);


Route::middleware('auth')->group(function () {
Route::get('/', function () { return view('dashboard'); });
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::get('/pos/add/{id}', [PosController::class, 'addCart'])->name('pos.add');
Route::get('/pos/remove/{id}', [PosController::class, 'removeCart'])->name('pos.remove');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
