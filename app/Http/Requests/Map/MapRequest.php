<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class MapRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => 'required_with:date_to|nullable|date',
            'date_to' => 'required_with:date_from|nullable|date',
            'users' => 'nullable|bool',
            'cameras' => 'nullable|bool',
            'low_priority' => 'nullable|bool',
            'middle_priority' => 'nullable|bool',
            'high_priority' => 'nullable|bool',
            'critical_priority' => 'nullable|bool',
            'categories' => 'nullable|array',
            'categories.*' => 'required|string|exists:categories,name',
        ];
    }
}
