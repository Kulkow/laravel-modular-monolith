<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Application\DTO\PermissionDto;
use App\Modules\Identity\Application\DTO\RoleDto;
use App\Modules\Identity\Application\Role\CreateRole\CreateRoleDto;
use App\Modules\Identity\Application\Role\CreateRole\CreateRoleUseCase;
use App\Modules\Identity\Application\Role\DropRole\DropRoleUseCase;
use App\Modules\Identity\Application\Role\UpdateRole\UpdateRoleDto;
use App\Modules\Identity\Application\Role\UpdateRole\UpdateRoleUseCase;
use App\Modules\Identity\Domain\Permission\PermissionRepository;
use App\Modules\Identity\Domain\Role\RoleId;
use App\Modules\Identity\Domain\Role\RoleName;
use App\Modules\Identity\Domain\Role\RoleRepository;
use App\Modules\Identity\Http\Requests\CreateRoleRequest;
use App\Modules\Identity\Http\Requests\UpdateRoleRequest;
use App\Modules\Identity\Http\Resources\RoleResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly RoleRepository       $roleRepository,
        private readonly PermissionRepository $permissionRepository,
        private readonly CreateRoleUseCase    $createRole,
        private readonly UpdateRoleUseCase    $updateRole,
        private readonly DropRoleUseCase    $dropRole,
    ) {}

    public function index()
    {
        $roles = $this->roleRepository->findAll();

        $dtos = array_map(fn($role) => new RoleDto(
            id:          $role->getId()->getValue(),
            name:        $role->getName()->getValue(),
            label:       $role->getLabel(),
            permissions: array_map(
                fn($perm) => new PermissionDto(
                    id:    $perm->getId() ?? 0,
                    code:  $perm->getCode()->getValue(),
                    name:  $perm->getName(),
                    group: $perm->getGroup(),
                ),
                $role->getPermissions()
            ),
        ), $roles);

        return $this->success(['roles' => RoleResource::collection($dtos)]);
    }

    public function show(int $id): RoleResource
    {
        $role = $this->roleRepository->findById(new RoleId($id));

        if ($role === null) {
            abort(404, "Роль #{$id} не найдена");
        }

        return new RoleResource(new RoleDto(
            id:          $role->getId()->getValue(),
            name:        $role->getName()->getValue(),
            label:       $role->getLabel(),
            permissions: array_map(
                fn($perm) => new PermissionDto(
                    id:    $perm->getId() ?? 0,
                    code:  $perm->getCode()->getValue(),
                    name:  $perm->getName(),
                    group: $perm->getGroup(),
                ),
                $role->getPermissions()
            ),
        ));
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $roleId = $this->createRole->execute(new CreateRoleDto(
                name:            $validated['name'],
                label:           $validated['label'],
                permissionCodes: $validated['permission_codes'] ?? [],
            ));

            return response()->json(['id' => $roleId], 201);
        } catch (\DomainException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->updateRole->execute(new UpdateRoleDto(
                roleId:          $id,
                name:            $validated['name'] ?? null,
                label:           $validated['label'] ?? null,
                permissionCodes: $validated['permission_codes'] ?? null,
            ));

            return response()->json(['message' => 'Роль обновлена']);
        } catch (\DomainException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function drop(int $id): JsonResponse
    {
        try {
            $this->dropRole->execute(new RoleId($id));
            return response()->json(['message' => 'Роль удалена']);
        } catch (\DomainException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    public function permissions(): JsonResponse
    {
        $permissions = $this->permissionRepository->findAll();

        $grouped = [];
        foreach ($permissions as $perm) {
            $grouped[$perm->getGroup()][] = [
                'id'   => $perm->getId(),
                'code' => $perm->getCode()->getValue(),
                'name' => $perm->getName(),
            ];
        }

        return response()->json($grouped);
    }
}
