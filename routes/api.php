<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// create user
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'permission:create-users'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
