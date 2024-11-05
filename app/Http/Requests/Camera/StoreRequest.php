<?php

namespace App\Http\Requests\Camera;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:64',
            'url' => 'required|string',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'port' => 'nullable|integer|max:65535',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }
}
