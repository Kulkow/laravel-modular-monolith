<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Application\Auth\GetCurrentUser\GetCurrentUserUseCase;
use App\Modules\Identity\Application\Auth\Login\LoginUseCase;
use App\Modules\Identity\Application\Auth\Logout\LogoutUseCase;
use App\Modules\Identity\Http\Resources\CurrentUserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description: 'Аутентификация')]
final class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly GetCurrentUserUseCase $getCurrentUser,
        private readonly LoginUseCase $login,
        private readonly LogoutUseCase $logout,
    ) {}

    #[OA\Get(
        path: "/api/identity/auth/user",
        summary: "Текущий пользователь со всеми разрешениями",
        security: [["sanctum" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Данные пользователя с полным списком разрешений",
            ),
            new OA\Response(response: 401, description: "Не авторизован"),
            new OA\Response(response: 500, description: "Внутренняя ошибка сервера"),
        ]
    )]
    public function user(Request $request): JsonResponse
    {
        try {
            $dto = $this->getCurrentUser->execute(Auth::id());
            return $this->success(['user' => new CurrentUserResource($dto)]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    #[OA\Post(
        path: "/api/identity/auth/login",
        summary: "Авторизация",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", example: "secret"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Успешная авторизация"),
            new OA\Response(response: 422, description: "Ошибка валидации / неверные данные"),
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        try {
            return $this->success($this->login->execute($request->all()));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    #[OA\Post(
        path: "/api/identity/auth/logout",
        summary: "Выход",
        security: [["sanctum" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(response: 200, description: "Успешный выход"),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        try {
            return $this->success($this->logout->execute());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


}
