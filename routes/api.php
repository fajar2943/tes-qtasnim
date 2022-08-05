<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit']);
    Route::put('categories/update', [CategoryController::class, 'update']);
    Route::delete('categories/destroy', [CategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::get('products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('products/update', [ProductController::class, 'update']);
    Route::delete('products/destroy', [ProductController::class, 'destroy']);
    
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/{id}', [TransactionController::class, 'show']);
    Route::get('transactions/{id}/edit', [TransactionController::class, 'edit']);
    Route::put('transactions/update', [TransactionController::class, 'update']);
    Route::delete('transactions/destroy', [TransactionController::class, 'destroy']);
});
