<?php


use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ShareholderController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ACTIVE + AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'active'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH USER BASIC ACTIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);

    Route::get('/me/permissions', [UserController::class, 'myPermissions']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY (USERS + ROLES + PERMISSIONS)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->group(function () {

        // USERS
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::post('/create-user', [UserController::class, 'createUser']);

        Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
        Route::get('/deleted-users', [UserController::class, 'deletedUsers']);
        Route::post('/users/{id}/restore-user', [UserController::class, 'restoreUser']);
        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

        Route::get('/users-with-roles', [UserController::class, 'index']);
        Route::get('/users/{id}/roles-permissions', [UserController::class, 'userRolePermission']);

        // ROLES
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles-with-permissions', [RoleController::class, 'withPermissions']);

        // PERMISSIONS
        Route::get('/permissions', [PermissionController::class, 'index']);

        // ROLE ASSIGNMENT
        Route::post('/users/{id}/assign-role', [UserRoleController::class, 'assignRole']);
        Route::delete('/users/{id}/remove-role', [UserRoleController::class, 'removeRole']);

        Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'assignPermissions']);
        Route::delete('/roles/{id}/remove-permission', [RolePermissionController::class, 'removePermission']);
    });

    /*
    |--------------------------------------------------------------------------
    | PERMISSION BASED ACTIONS (NON ADMIN USERS)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['permission:create-users'])->group(function () {
        Route::post('/create-user', [UserController::class, 'createUser']);
    });

    /*
    |--------------------------------------------------------------------------
    | PROJECT MODULE (ERP CORE)
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| PROJECT MODULE (ERP CORE)
|--------------------------------------------------------------------------
*/

    /*
    |--------------------------------------------------------------------------
    | PROJECT MODULE (ERP CORE)
    |--------------------------------------------------------------------------
    */

// 1. ADMIN / SUPER ADMIN → FULL ACCESS TO ALL PROJECTS (no project permission check)
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::post('/projects', [ProjectController::class, 'store']);           // Create any project
        Route::get('/projects', [ProjectController::class, 'index']);            // List all projects

        // Full CRUD on any project ID (bypasses project-specific permission)
        Route::get('/projects/{id}', [ProjectController::class, 'show']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
        Route::post('/projects/{id}/restore', [ProjectController::class, 'restore']);

        // Project user management (assign users & their project roles)
        Route::post('/projects/{id}/assign-user', [ProjectUserController::class, 'assignUser']);
        Route::delete('/projects/{id}/remove-user', [ProjectUserController::class, 'removeUser']);
        Route::get('/projects/{id}/users', [ProjectController::class, 'users']);

        // Admin-only extra endpoints
        Route::get('/projects/{id}/permission-matrix', [ProjectController::class, 'permissionMatrix']);
    });

// 2. REGULAR USERS → Project-based permission only (NON-admins)
    Route::middleware(['auth:sanctum'])->group(function () {

        // These routes will ONLY be reached by users who are NOT admins
        // because the admin group above already matched the same URIs

        Route::get('/projects/{id}', [ProjectController::class, 'show'])
            ->middleware('project.permission:view-projects');

        Route::put('/projects/{id}', [ProjectController::class, 'update'])
            ->middleware('project.permission:edit-projects');

        Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])
            ->middleware('project.permission:delete-projects');
    });


    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::get('/shareholders', [ShareholderController::class, 'index']);
        Route::post('/shareholders', [ShareholderController::class, 'store']);
        Route::get('/shareholders/{id}', [ShareholderController::class, 'show']);
        Route::put('/shareholders/{id}', [ShareholderController::class, 'update']);
        Route::delete('/shareholders/{id}', [ShareholderController::class, 'destroy']);

    });

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::get('/currencies', [CurrencyController::class, 'index']);
        Route::post('/currencies', [CurrencyController::class, 'store']);
        Route::get('/currencies/{id}', [CurrencyController::class, 'show']);
        Route::put('/currencies/{id}', [CurrencyController::class, 'update']);
        Route::delete('/currencies/{id}', [CurrencyController::class, 'destroy']);


    });

});
