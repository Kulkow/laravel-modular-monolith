<?php
namespace App\Modules\Identity\Listeners;

use App\Events\RegisterApiRoutes;
use App\Modules\Identity\Http\Controllers\Api\AuthController;
use App\Modules\Identity\Http\Controllers\Api\LoginHistoryController;
use App\Modules\Identity\Http\Controllers\Api\RoleController;
use App\Modules\Identity\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

class RegisterIdentityRoutes
{
    public function handle(RegisterApiRoutes $event): void
    {
        $event->router->prefix('identity')->name('users.')->group(function () use ($event) {

            Route::post('/auth/login', [AuthController::class, 'login'])
                ->name('auth.login')
                ->withoutMiddleware(['auth:sanctum']);
            Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::get('/auth/user', [AuthController::class, 'user'])->name('auth.user');

            Route::get('/users', [UserController::class, 'index'])->name('index');
            Route::get('/user/{id}', [UserController::class, 'show'])->name('show');
            Route::post('/users/', [UserController::class, 'store'])->name('store');
            Route::patch('/user/{id}', [UserController::class, 'update'])->name('update');
            Route::patch('/user/{id}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
            Route::post('/user/{id}/roles', [UserController::class, 'assignRole'])->name('roles.assign');
            Route::delete('/user/{id}/roles', [UserController::class, 'revokeRole'])->name('roles.revoke');
            Route::post('/user/{id}/auto-login', [UserController::class, 'autoLogin'])->name('auto-login');
            Route::post('/user/{id}/change-password', [UserController::class, 'changePassword'])->name('change-password');

            Route::get('/roles/', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
            Route::post('/roles/', [RoleController::class, 'store'])->name('roles.store');
            Route::patch('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/roles/{id}', [RoleController::class, 'drop'])->name('roles.drop');
            Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions.index');

            Route::get('/login-histories', [LoginHistoryController::class, 'index'])->name('login-histories.index');
            Route::get('/login-histories/{id}/user', [LoginHistoryController::class, 'byUser'])->name('login-histories.user');

        });
    }
}
