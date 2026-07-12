<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Application\Role\AssignRole\AssignRoleDto;
use App\Modules\Identity\Application\Role\AssignRole\AssignRoleUseCase;
use App\Modules\Identity\Application\Role\RevokeRole\RevokeRoleDto;
use App\Modules\Identity\Application\Role\RevokeRole\RevokeRoleUseCase;
use App\Modules\Identity\Application\User\AutoLogin\AutoLoginUserUseCase;
use App\Modules\Identity\Application\User\ChangePassword\ChangePasswordDto;
use App\Modules\Identity\Application\User\ChangePassword\ChangePasswordUseCase;
use App\Modules\Identity\Application\User\CreateUser\CreateUserDto;
use App\Modules\Identity\Application\User\CreateUser\CreateUserUseCase;
use App\Modules\Identity\Application\User\DeactivateUser\DeactivateUserUseCase;
use App\Modules\Identity\Application\User\GetUser\GetUserUseCase;
use App\Modules\Identity\Application\User\ListUsers\ListUsersUseCase;
use App\Modules\Identity\Application\User\UpdateUser\UpdateUserDto;
use App\Modules\Identity\Application\User\UpdateUser\UpdateUserUseCase;
use App\Modules\Identity\Domain\Access\AccessChecker;
use App\Modules\Identity\Domain\Access\Permissions\IdentityPermission;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Http\Requests\AssignEmployeeRequest;
use App\Modules\Identity\Http\Requests\AssignRoleRequest;
use App\Modules\Identity\Http\Requests\CreateUserRequest;
use App\Modules\Identity\Http\Requests\UpdateUserRequest;
use App\Modules\Identity\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Документация API - модуля Identity", version: "1.0.0")]
#[OA\Tag(name: 'User', description: 'Управление пользователями')]
class UserController extends Controller
{
    use ApiResponse;
    public function __construct(
        private readonly CreateUserUseCase     $createUser,
        private readonly UpdateUserUseCase     $updateUser,
        private readonly DeactivateUserUseCase $deactivateUser,
        private readonly GetUserUseCase        $getUser,
        private readonly ListUsersUseCase      $listUsers,
        private readonly AssignRoleUseCase     $assignRole,
        private readonly RevokeRoleUseCase     $revokeRole,
        private readonly AutoLoginUserUseCase  $autoLoginUser,
        private readonly ChangePasswordUseCase  $changePassword,
        private readonly AccessChecker $accessChecker
    ) {}

    #[OA\Get(
        path: "/api/identity/users",
        summary: "Список пользователей",
        security: [["sanctum" => []]],
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ",
                content: new OA\JsonContent(ref: "#/components/schemas/ListUsersResponse"),
            ),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function index()
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ViewUsers,
            );
            $dtos = $this->listUsers->execute();
            $data = [
                'users' => UserResource::collection($dtos),
            ];
            return $this->success($data);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    #[OA\Get(
        path: "/api/identity/users/{id}",
        summary: "Получить пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ",
                content: new OA\JsonContent(ref: "#/components/schemas/GetUserResponse"),
            ),
            new OA\Response(response: 404, description: "Пользователь не найден"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function show(int $id)
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ViewUsers,
            );
            $dto = $this->getUser->execute($id);
            return new UserResource($dto);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }

    }

    #[OA\Post(
        path: "/api/identity/users",
        summary: "Создать пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/CreateUserRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Пользователь создан",
                content: new OA\JsonContent(ref: "#/components/schemas/CreateUserResponse"),
            ),
            new OA\Response(response: 422, description: "Ошибка валидации"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function store(CreateUserRequest $request): JsonResponse
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            $validated = $request->validated();
            $userId = $this->createUser->execute(new CreateUserDto(
                email:   $validated['email'],
                name:    $validated['name'],
                password: $validated['password'],
                roleIds: $validated['role_ids'] ?? null,
            ));
            return $this->success([
                'id' => $userId,
            ], 'Пользователь обновлён', 201);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    #[OA\Patch(
        path: "/api/identity/users/{id}",
        summary: "Обновить пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateUserRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Пользователь обновлён",
                content: new OA\JsonContent(ref: "#/components/schemas/UpdateUserResponse"),
            ),
            new OA\Response(response: 404, description: "Пользователь не найден"),
            new OA\Response(response: 422, description: "Ошибка валидации"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            $validated = $request->validated();
            $this->updateUser->execute(new UpdateUserDto(
                userId: $id,
                name:   $validated['name'] ?? null,
                email:  $validated['email'] ?? null,
            ));
            return $this->success(null, 'Пользователь обновлён');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }

    }

    #[OA\Patch(
        path: "/api/identity/users/{id}/deactivate",
        summary: "Деактивировать пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Пользователь деактивирован",
                content: new OA\JsonContent(ref: "#/components/schemas/DeactivateUserResponse"),
            ),
            new OA\Response(response: 404, description: "Пользователь не найден"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function deactivate(int $id): JsonResponse
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            $this->deactivateUser->execute($id);
            return $this->success(null, 'Пользователь деактивирован');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }

    }

    #[OA\Post(
        path: "/api/identity/users/{id}/roles",
        summary: "Назначить роль пользователю",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AssignRoleRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Роль назначена",
                content: new OA\JsonContent(ref: "#/components/schemas/AssignRoleResponse"),
            ),
            new OA\Response(response: 404, description: "Пользователь или роль не найдены"),
            new OA\Response(response: 422, description: "Ошибка валидации"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function assignRole(AssignRoleRequest $request, int $id): JsonResponse
    {
        try{
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            $this->assignRole->execute(new AssignRoleDto(
                userId: $id,
                roleId: $request->validated('role_id'),
            ));
            return $this->success(null, 'Роль назначена');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    #[OA\Delete(
        path: "/api/identity/users/{id}/roles",
        summary: "Отозвать роль у пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AssignRoleRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Роль снята",
                content: new OA\JsonContent(ref: "#/components/schemas/RevokeRoleResponse"),
            ),
            new OA\Response(response: 404, description: "Пользователь не найден"),
            new OA\Response(response: 422, description: "Ошибка валидации"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function revokeRole(AssignRoleRequest $request, int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            $this->revokeRole->execute(new RevokeRoleDto(
                userId: $id,
                roleId: $request->validated('role_id'),
            ));
            return $this->success(null, 'Роль снята');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }




    #[OA\Post(
        path: "/api/identity/users/auto-login/{id}",
        summary: "Авторизовать пользователя",
        security: [["sanctum" => []]],
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Успешный выход"),
        ]
    )]
    public function autoLogin(int $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $this->accessChecker->assertPermission(
                new UserId($userId),
                IdentityPermission::ManageUsers,
            );
            return $this->success($this->autoLoginUser->execute($id));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function changePassword(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $message = $this->changePassword->execute(new ChangePasswordDto($id, $validated['password']));
            return $this->success([], $message);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
}
