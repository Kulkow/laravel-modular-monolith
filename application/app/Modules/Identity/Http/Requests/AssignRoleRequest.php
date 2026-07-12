<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AssignRoleRequest",
    required: ["role_id"],
    properties: [
        new OA\Property(property: "role_id", type: "integer", example: 1),
    ],
    type: "object"
)]
class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Роль обязательна для выбора',
            'role_id.exists'   => 'Выбранная роль не найдена',
        ];
    }
}
