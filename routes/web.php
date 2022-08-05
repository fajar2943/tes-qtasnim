<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::delete('/transactions/destroy', [TransactionController::class, 'destroy']);
Route::delete('/categories/destroy', [CategoryController::class, 'destroy']);
Route::delete('/products/destroy', [ProductController::class, 'destroy']);

// Route::get('/transactions', [TransactionController::class, 'index']);
// Route::post('/transactions', [TransactionController::class, 'store']);
// Route::get('/transactions/{id}', [TransactionController::class, 'show']);
// Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit']);
// Route::put('/transactions/update', [TransactionController::class, 'update']);
// Route::delete('/transactions/destroy', [TransactionController::class, 'destroy']);

Route::resource('/transactions', TransactionController::class);
Route::resource('/categories', CategoryController::class);
Route::resource('/products', ProductController::class);