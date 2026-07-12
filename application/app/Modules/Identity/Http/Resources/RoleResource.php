<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Resources;

use App\Modules\Identity\Application\DTO\RoleDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin RoleDto
 */
class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var RoleDto $dto */
        $dto = $this->resource;

        return [
            'id'          => $dto->id,
            'name'        => $dto->name,
            'label'       => $dto->label,
            'permissions' => array_map(fn($perm) => [
                'id'    => $perm->id,
                'code'  => $perm->code,
                'name'  => $perm->name,
                'group' => $perm->group,
            ], $dto->permissions),
        ];
    }
}
