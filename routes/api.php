<?php

use App\Http\Controllers\Api\FavoriteListController;
use App\Http\Controllers\Api\FavoriteListProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('simple_auth')->group(function () {
    Route::apiResource('lists', FavoriteListController::class);
    Route::post('lists/{listId}/products', [FavoriteListProductController::class, 'store']);
    Route::delete('lists/{listId}/products/{sku}', [FavoriteListProductController::class, 'destroy']);
});
