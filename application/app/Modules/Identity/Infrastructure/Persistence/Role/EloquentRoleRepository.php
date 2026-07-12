<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\Role;

use App\Modules\Identity\Domain\Permission\Permission;
use App\Modules\Identity\Domain\Permission\PermissionCode;
use App\Modules\Identity\Domain\Role\PredefinedRole;
use App\Modules\Identity\Domain\Role\Role;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Role\RoleRepository;

final class EloquentRoleRepository implements RoleRepository
{
    public function findById(RoleId $id): ?Role
    {
        $model = RoleModel::query()->with('permissions')->find($id->getValue());
        return $model ? $this->toDomain($model) : null;
    }

    public function findByName(RoleName $name): ?Role
    {
        $model = RoleModel::query()
            ->with('permissions')
            ->where('name', $name->getValue())
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return RoleModel::query()
            ->with('permissions')
            ->get()
            ->map(fn(RoleModel $model) => $this->toDomain($model))
            ->all();
    }

    public function save(Role $role): RoleId
    {
        if ($role->getId() === null) {
            $model = new RoleModel();
        } else {
            $model = RoleModel::query()->findOrFail($role->getId()->getValue());
        }

        $model->name       = $role->getName()->getValue();
        $model->label      = $role->getLabel();
        $model->guard_name = 'sanctum';
        $model->save();

        $permissionCodes = array_map(
            fn(Permission $perm) => $perm->getCode()->getValue(),
            $role->getPermissions()
        );
        $model->syncPermissions($permissionCodes);

        return new RoleId($model->id);
    }

    public function delete(RoleId $id): void
    {
        RoleModel::query()->where('id', $id->getValue())->delete();
    }

    private function toDomain(RoleModel $model): Role
    {
        $permissions = [];
        $fullAllow = false;
        $id = 1000;
        $predefined = PredefinedRole::tryFrom($model->name);
        if ($predefined !== null) {
            if(! $predefined->fullAllow()){
                foreach ($predefined->permissions() as $permission) {
                    $permissions[] = Permission::restore(
                        id:   $id++,
                        code:  new PermissionCode($permission->value),
                        name:  $permission->label(),
                        group: $permission->group(),
                    );
                }

            }
        }



        /*if ($model->relationLoaded('permissions')) {
            foreach ($model->permissions as $perm) {
                $permissions[] = Permission::restore(
                    id:    $perm->id,
                    code:  new PermissionCode($perm->name),
                    name:  $perm->name,
                    group: 'general',
                );
            }
        }*/

        return Role::restore(
            id:          new RoleId($model->id),
            name:        new RoleName($model->name),
            label:       $model->label ?? $model->name,
            permissions: $permissions,
        );
    }
}
