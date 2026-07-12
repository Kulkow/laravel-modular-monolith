<?php

declare(strict_types=1);

use App\Modules\Identity\Http\Controllers\Api\RoleController;
use App\Modules\Identity\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('identity')->name('identity.')->group(function () {

    // Пользователи
  /*  Route::prefix('users')->name('users.')->group(function () {
        Route::get('{id}', [UserController::class, 'show'])->name('show');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('{id}', [UserController::class, 'update'])->name('update');
        Route::patch('{id}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');

        // Роли пользователя
        Route::post('{id}/roles', [UserController::class, 'assignRole'])->name('roles.assign');
        Route::delete('{id}/roles', [UserController::class, 'revokeRole'])->name('roles.revoke');
    });

    // Роли и права
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('{id}', [RoleController::class, 'show'])->name('show');
    });

    Route::get('permissions', [RoleController::class, 'permissions'])->name('permissions.index');*/
});
