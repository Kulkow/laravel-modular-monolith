<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Resources;

use App\Modules\Identity\Application\Auth\GetCurrentUser\CurrentUserDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CurrentUserDto
 */
class CurrentUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var CurrentUserDto $dto */
        $dto = $this->resource;

        return [
            'id'          => $dto->id,
            'email'       => $dto->email,
            'name'        => $dto->name,
            'status'      => [
                'id'   => $dto->statusId,
                'name' => $dto->statusName,
            ],
            'roles'       => array_map(fn($role) => [
                'id'    => $role->id,
                'name'  => $role->name,
                'label' => $role->label,
            ], $dto->roles),
            'permissions' => $dto->permissions,
            'full_access' => $dto->fullAccess,
            'created_at'  => $dto->createdAt,
        ];
    }
}
