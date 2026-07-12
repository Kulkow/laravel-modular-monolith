<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUserRequest",
    properties: [
        new OA\Property(property: "name", type: "string", nullable: true, example: "Иван Иванов"),
        new OA\Property(property: "email", type: "string", format: "email", nullable: true, example: "user@example.com"),
    ],
    type: "object"
)]
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name'  => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'  => 'Неверный формат email',
            'email.unique' => 'Пользователь с таким email уже существует',
        ];
    }
}
