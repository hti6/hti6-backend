<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSelfRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:3|max:64'
        ];
    }
}
