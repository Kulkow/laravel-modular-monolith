<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AssignEmployeeRequest",
    required: ["employee_id"],
    properties: [
        new OA\Property(property: "employee_id", type: "integer", example: 1),
    ],
    type: "object"
)]
class AssignEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', ],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Сотрудник обязателен для выбора',
        ];
    }
}
