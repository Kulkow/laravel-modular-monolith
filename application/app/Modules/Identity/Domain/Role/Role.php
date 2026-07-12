<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Role;

use App\Modules\Identity\Domain\Permission\Permission;
use App\Modules\Identity\Domain\Permission\PermissionCode;

final class Role
{
    /** @param Permission[] $permissions */
    private function __construct(
        private readonly ?RoleId $id,
        private RoleName         $name,
        private string           $label,
        private array            $permissions,
    ) {}

    public static function create(
        RoleName $name,
        string   $label,
    ): self {
        return new self(
            id:          null,
            name:        $name,
            label:       $label,
            permissions: [],
        );
    }

    public static function restore(
        RoleId   $id,
        RoleName $name,
        string   $label,
        array    $permissions,
    ): self {
        return new self(
            id:          $id,
            name:        $name,
            label:       $label,
            permissions: $permissions,
        );
    }

    public function givePermission(Permission $permission): void
    {
        if ($this->hasPermission($permission->getCode())) {
            return;
        }
        $this->permissions[] = $permission;
    }

    public function revokePermission(PermissionCode $code): void
    {
        foreach ($this->permissions as $index => $perm) {
            if ($perm->getCode()->equals($code)) {
                unset($this->permissions[$index]);
                $this->permissions = array_values($this->permissions);
                return;
            }
        }
    }

    public function getId(): ?RoleId
    {
        return $this->id;
    }

    public function getName(): RoleName
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /** @return Permission[] */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function rename(RoleName $name): void
    {
        $this->name = $name;
    }

    public function relabel(string $label): void
    {
        $this->label = $label;
    }

    /** @param Permission[] $permissions */
    public function syncPermissions(array $permissions): void
    {
        $this->permissions = [];
        foreach ($permissions as $permission) {
            $this->givePermission($permission);
        }
    }

    public function hasPermission(PermissionCode $code): bool
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getCode()->equals($code)) {
                return true;
            }
        }
        return false;
    }
}
