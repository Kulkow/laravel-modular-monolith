<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:125', 'unique:roles,name'],
            'label'              => ['required', 'string', 'max:255'],
            'permission_codes'   => ['sometimes', 'array'],
            'permission_codes.*' => ['string'],
        ];
    }
}
