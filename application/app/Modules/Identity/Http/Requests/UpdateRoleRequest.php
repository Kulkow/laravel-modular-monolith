<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'name'               => ['sometimes', 'string', 'max:125', Rule::unique('roles', 'name')->ignore($id)],
            'label'              => ['sometimes', 'string', 'max:255'],
            'permission_codes'   => ['sometimes', 'array'],
            'permission_codes.*' => ['string'],
        ];
    }
}
