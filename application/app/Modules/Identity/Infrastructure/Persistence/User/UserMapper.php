<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\User;

use App\Modules\Identity\Domain\Permission\Permission;
use App\Modules\Identity\Domain\Permission\PermissionCode;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\User\User;
use App\Modules\Identity\Domain\User\UserEmail;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Domain\User\UserStatus;
use App\Modules\Identity\Infrastructure\Persistence\Role\RoleModel;

final class UserMapper
{
    public static function toDomain(UserModel $model): User
    {
        $roles = [];

        if ($model->relationLoaded('roles')) {
            foreach ($model->roles as $roleModel) {
                $roles[] = self::roleToDomain($roleModel);
            }
        }

        return User::restore(
            id:        new UserId($model->id),
            email:     new UserEmail($model->email),
            name:      $model->name,
            password: null,
            status:    new UserStatus($model->status_id ?? UserStatus::ACTIVE),
            roles:     $roles,
            createdAt: new \DateTimeImmutable($model->created_at->toString()),
        );
    }

    private static function roleToDomain(RoleModel $model): Role
    {
        $permissions = [];

        if ($model->relationLoaded('permissions')) {
            foreach ($model->permissions as $permModel) {
                $permissions[] = Permission::restore(
                    id:    $permModel->id,
                    code:  new PermissionCode($permModel->name),
                    name:  $permModel->name,
                    group: 'general',
                );
            }
        }

        return Role::restore(
            id:          new RoleId($model->id),
            name:        new RoleName($model->name),
            label:       $model->label ?? $model->name,
            permissions: $permissions,
        );
    }
}
