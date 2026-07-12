<?php

declare(strict_types=1);

namespace App\Modules\Identity\Providers;

use App\Events\RegisterApiRoutes;
use App\Modules\Identity\Console\Commands\AttachRoleToUser;
use App\Modules\Identity\Console\Commands\UserListCommand;
use App\Modules\Identity\Domain\Access\AccessChecker;
use App\Modules\Identity\Domain\Auth\AuthService;

use App\Modules\Identity\Domain\Auth\Repository\LoginHistoryRepositoryInterface;
use App\Modules\Identity\Infrastructure\Auth\TokenAuthService;
use App\Modules\Identity\Infrastructure\Persistence\LoginHistory\EloquentLoginHistoryRepository;
use App\Modules\Identity\Listeners\LogFailedLogin;
use App\Modules\Identity\Listeners\LogSuccessfulLogin;
use App\Modules\Identity\Listeners\RegisterIdentityRoutes;
use App\Modules\Identity\Domain\Permission\PermissionRepository;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Domain\User\UserRepository;
use App\Modules\Identity\Infrastructure\Access\SpatieAccessChecker;
use App\Modules\Identity\Infrastructure\Persistence\Permission\EloquentPermissionRepository;
use App\Modules\Identity\Infrastructure\Persistence\Permission\PermissionModel;
use App\Modules\Identity\Infrastructure\Persistence\Role\EloquentRoleRepository;
use App\Modules\Identity\Infrastructure\Persistence\Role\RoleModel;
use App\Modules\Identity\Infrastructure\Persistence\User\EloquentUserRepository;
use App\Modules\Identity\Infrastructure\Persistence\User\UserModel;

use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->bind(
            \Spatie\Permission\Models\Role::class,
            RoleModel::class,
        );
        $this->app->bind(
            \Spatie\Permission\Models\Permission::class,
            PermissionModel::class,
        );

        $this->commands([
            AttachRoleToUser::class,
            UserListCommand::class,
        ]);

        $this->app->bind(AuthService::class, TokenAuthService::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
        $this->app->bind(PermissionRepository::class, EloquentPermissionRepository::class);
        $this->app->bind(AccessChecker::class, SpatieAccessChecker::class);
        $this->app->bind(LoginHistoryRepositoryInterface::class,EloquentLoginHistoryRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(app_path("Modules/Identity/database/migrations"));

        Event::listen(
            \Illuminate\Auth\Events\Login::class,
            LogSuccessfulLogin::class,
        );
        Event::listen(
            \Illuminate\Auth\Events\Failed::class,
            LogFailedLogin::class,
        );



        config(['auth.providers.users.model' => UserModel::class]);
    }
}
