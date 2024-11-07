<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:64',
            'login' => 'nullable|string|max:64|unique:users,login',
            'password' => 'nullable|string|max:64',
        ];
    }
}
