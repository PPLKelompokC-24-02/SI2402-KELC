<?php

use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\ProfessionController as AdminProfessionController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('professions', [ProfessionController::class, 'index']);
        Route::get('professions/{profession}', [ProfessionController::class, 'show'])->whereNumber('profession');
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::apiResource('professions', AdminProfessionController::class)->whereNumber('profession');
        Route::get('career-categories', [MasterDataController::class, 'categories']);
        Route::get('competencies', [MasterDataController::class, 'competencies']);
        Route::get('roles', [MasterDataController::class, 'roles']);
        Route::get('users', [UserRoleController::class, 'index']);
        Route::put('users/{user}/role', [UserRoleController::class, 'update'])->whereNumber('user');
    });

    // A protected identity endpoint reserves the assessor area; no assessment features.
    Route::get('assessor/me', [AuthController::class, 'me'])->middleware('role:assessor');
});
