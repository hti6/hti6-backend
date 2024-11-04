<?php

namespace App\Http\Requests\DamageRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'photo_url' => 'required|string|url'
        ];
    }
}
