<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Resources;

use App\Modules\Identity\Application\DTO\UserDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserDto
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var UserDto $dto */
        $dto = $this->resource;

        return [
            'id'          => $dto->id,
            'email'       => $dto->email,
            'name'        => $dto->name,
            'status'      => [
                'id'   => $dto->statusId,
                'name' => $dto->statusName,
            ],
            'roles'      => array_map(fn($role) => [
                'id'    => $role->id,
                'name'  => $role->name,
                'label' => $role->label,
            ], $dto->roles),
            'created_at' => $dto->createdAt,
            'employee' => $dto->employeeUserDto ? [
                'id'   => $dto->employeeUserDto->id,
                'fullName' => $dto->employeeUserDto->fullName,
                'departmentName' => $dto->employeeUserDto->departmentName,
                'position' => $dto->employeeUserDto->position,
            ] : []
        ];
    }
}
