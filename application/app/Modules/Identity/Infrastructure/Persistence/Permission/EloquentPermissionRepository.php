<?php

declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\Permission;

use App\Modules\Identity\Domain\Permission\Permission;
use App\Modules\Identity\Domain\Permission\PermissionCode;
use App\Modules\Identity\Domain\Permission\PermissionRepository;

final class EloquentPermissionRepository implements PermissionRepository
{
    public function findByCode(PermissionCode $code): ?Permission
    {
        $model = PermissionModel::query()
            ->where('name', $code->getValue())
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return PermissionModel::query()
            ->get()
            ->map(fn(PermissionModel $model) => $this->toDomain($model))
            ->all();
    }

    public function findByGroup(string $group): array
    {
        return PermissionModel::query()
            ->where('name', 'like', $group . '.%')
            ->get()
            ->map(fn(PermissionModel $model) => $this->toDomain($model))
            ->all();
    }

    public function save(Permission $permission): int
    {
        $model = PermissionModel::query()->firstOrCreate(
            ['name'       => $permission->getCode()->getValue()],
            ['guard_name' => 'web'],
        );

        return $model->id;
    }

    private function toDomain(PermissionModel $model): Permission
    {
        $parts = explode('.', $model->name, 2);
        $group = $parts[0] ?? 'general';

        return Permission::restore(
            id:    $model->id,
            code:  new PermissionCode($model->name),
            name:  $model->name,
            group: $group,
        );
    }
}
