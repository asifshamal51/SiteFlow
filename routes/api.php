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
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    // protected routes from inactive users

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
        Route::get('/deleted-users', [UserController::class, 'deletedUsers']);
        Route::post('/users/{id}/restore-user', [UserController::class, 'restoreUser']);
        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::post('/create-user', [UserController::class, 'createUser']);
        Route::get('/users/{id}', [UserController::class, 'show']);

    });

// User Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/me/permissions', [UserController::class, 'myPermissions']);
});


// PERMISSION: CRATE-USER
Route::middleware(['auth:sanctum', 'permission:create-users'])->group(function () {
    Route::post('/create-user', [UserController::class, 'createUser']);
});


Route::get('/users/{id}/roles-permissions', [UserController::class, 'userRolePermission'])
    ->middleware(['auth:sanctum']);


// Roles and Permissions Endpoints
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Roles list
    Route::get('/roles', [RoleController::class, 'index']);

    Route::get('/users-with-roles', [UserController::class, 'index']);

    // Permissions list
    Route::get('/permissions', [PermissionController::class, 'index']);

    // BONUS: roles with permissions
    Route::get('/roles-with-permissions', [RoleController::class, 'withPermissions']);

    Route::post('/users/{id}/assign-role', [UserRoleController::class, 'assignRole']);

    Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'assignPermissions']);

    Route::delete('/users/{id}/remove-role', [UserRoleController::class, 'removeRole']);

    Route::delete('/roles/{id}/remove-permission', [RolePermissionController::class, 'removePermission']);

});

});
