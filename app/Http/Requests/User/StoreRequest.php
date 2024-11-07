<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64',
            'login' => 'required|string|max:64|unique:users,login',
            'password' => 'required|string|max:64',
        ];
    }
}
