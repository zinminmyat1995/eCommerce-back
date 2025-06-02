<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/user/login',[App\Http\Controllers\ApiControllers\UserRegistrationController::class,"login"]);
Route::post('/user/register',[App\Http\Controllers\ApiControllers\LoginController::class,"register"]);
Route::post('/place-order',[App\Http\Controllers\ApiControllers\LoginController::class,"placeOrder"]);
Route::get('/get-orders/{id}',[App\Http\Controllers\ApiControllers\LoginController::class,"getOrdersByUserId"]);
Route::post('/cancel-order',[App\Http\Controllers\ApiControllers\LoginController::class,"cancelOrder"]);
Route::post('/login',[App\Http\Controllers\ApiControllers\LoginController::class,"login"]);
Route::get('/get-products',[App\Http\Controllers\ApiControllers\LoginController::class,"getProducts"]);
// Route::middleware('auth:sanctum')->get('/get-orders/{id}', [App\Http\Controllers\ApiControllers\LoginController::class, 'getOrdersByUserId']);

