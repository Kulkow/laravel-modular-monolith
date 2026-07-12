<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateUserRequest",
    required: ["name", "email", "password", "password_confirmation"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "Иван Иванов"),
        new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
        new OA\Property(property: "password", type: "string", minLength: 8, example: "secret123"),
        new OA\Property(property: "password_confirmation", type: "string", example: "secret123"),
        new OA\Property(
            property: "role_ids",
            type: "array",
            nullable: true,
            items: new OA\Items(type: "integer", example: 1)
        ),
    ],
    type: "object"
)]
class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Имя обязательно для заполнения',
            'email.required'    => 'Email обязателен для заполнения',
            'email.email'       => 'Неверный формат email',
            'email.unique'      => 'Пользователь с таким email уже существует',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min'      => 'Пароль должен содержать минимум 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
