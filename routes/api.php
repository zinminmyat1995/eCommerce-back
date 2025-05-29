<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/getUserList', [UserController::class, 'index']);
Route::post('/changeAliceName', [UserController::class, 'changeAliceName']);
Route::get('/getUserListApi', [UserController::class, 'index']);
Route::post('/postUserListApi', [UserController::class, 'index']);

Route::middleware([CustomAuthMiddleware::class])->group(function () {
// Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/myApi/extractUserListApi', [UserController::class, 'index']);  
});

Route::post('/login', [UserController::class, 'login']);