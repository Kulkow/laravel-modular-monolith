<?php

declare(strict_types=1);

namespace App\Modules\Identity\Domain\Permission;

final class Permission
{
    private function __construct(
        private readonly ?int    $id,
        private PermissionCode   $code,
        private string           $name,
        private string           $group,
    ) {}

    public static function create(
        PermissionCode $code,
        string         $name,
        string         $group,
    ): self {
        return new self(id: null, code: $code, name: $name, group: $group);
    }

    public static function restore(
        int            $id,
        PermissionCode $code,
        string         $name,
        string         $group,
    ): self {
        return new self(id: $id, code: $code, name: $name, group: $group);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): PermissionCode
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroup(): string
    {
        return $this->group;
    }
}
