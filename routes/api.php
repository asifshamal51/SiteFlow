<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
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



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users-with-roles', [UserController::class, 'index']);
});

Route::get('/users/{id}/roles-permissions', [UserController::class, 'show'])
    ->middleware(['auth:sanctum']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::post('/users/{id}/assign-role', [UserRoleController::class, 'assignRole']);

    Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'assignPermissions']);

    Route::delete('/users/{id}/remove-role', [UserRoleController::class, 'removeRole']);

    Route::delete('/roles/{id}/remove-permission', [RolePermissionController::class, 'removePermission']);
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Roles list
    Route::get('/roles', [RoleController::class, 'index']);

    // Permissions list
    Route::get('/permissions', [PermissionController::class, 'index']);

    // BONUS: roles with permissions
    Route::get('/roles-with-permissions', [RoleController::class, 'withPermissions']);
});
